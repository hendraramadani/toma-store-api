<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TemporaryCart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function storeOrder(Request $request){

        

        $user_id = $request->get('user_id');
        $data = json_decode($request['data']);
        $total_cost = 0;

        
        foreach ($data as $index) {
            $total_cost = $total_cost + $index->totalPrice;
          }

        $order = new Order();
        $order->user_id = (int)$user_id;
        $order->status_order_id = 3;
        $order->total_cost = $total_cost;
        $order->save();

        $user = User::where('id', '=', $user_id)->first();
        $order->name = $user->name;

        // dd($order->id);

        foreach ($data as $index) {
            $orderDetail = new OrderDetail();
            $orderDetail->orders_id = $order->id;
            $orderDetail->product_id = $index->product_id;
            $orderDetail->amount= $index->quantity;
            $orderDetail->cost = $index->totalPrice;
            $orderDetail->save();
          }


          $cart = TemporaryCart::where('user_id', '=', $user_id)->first();
          $cart->data       = array();
          $cart->save();

          return response()->json(array([
            'order' => $order,
            'detail' => $data,
        ]), 200);
        ///order detail


    }

    public function getUserOrder(Request $request){
        $response = array();
        $user_id = $request->get('user_id');
        $public_storage = config('const.public_storage');


        $order = Order::where('orders.user_id', '=', $user_id)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->leftJoin('couriers as c', 'orders.courier_id', '=', 'c.id')
        ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*', 'u.name as courier_name','u.phone as courier_phone','users.name','users.address','status_orders.status')
        ->get();
        // dd($order);
        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->orderBy('stores.id', 'asc')
            ->select('orders_detail.*', 'products.name as product_name', 'stores.name as store_name',
                    DB::raw("CONCAT('$public_storage',`products`.`image`)  AS product_image"))
            ->get();
            $response[$key]['order'] =  $index;
            $response[$key]['detail'] =  $orderdetail;
            
        }

        return response()->json($response
        , 200);

    }


    public function doneOrderUser(Request $request){
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->status_order_id = 4;
        $response->save();


        return response()->json($response
        , 201);
    }

    public function cancelOrderUser(Request $request){
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->status_order_id = 5;
        $response->save();


        return response()->json($response
        , 201);
    }

    public function getAdminOrder(Request $request){
        $response = array();
        $public_storage = config('const.public_storage');


        $order = Order::leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->leftJoin('couriers as c', 'orders.courier_id', '=', 'c.id')
        ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
        ->orderBy('orders.id', 'DESC')
        ->select('orders.*', 'u.name as courier_name','users.name','users.address','status_orders.status')
        ->get();
        // dd($order);
        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->orderBy('stores.id', 'asc')
            ->select('orders_detail.*', 'products.name as product_name', 'stores.name as store_name',
                    DB::raw("CONCAT('$public_storage',`products`.`image`)  AS product_image"))
            ->get();
            $response[$key]['order'] =  $index;
            $response[$key]['detail'] =  $orderdetail;
            
        }

        return response()->json($response
        , 200);

    }


    public function getCountOrdersByMonth(){ //must include year,but hardcoded
        $year = 2025;
        
        
        // ->groupBy(function($date) {
        //     //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
        //     return Carbon::parse($date->created_at)->format('m'); // grouping by months
        // });
        $response= array();
        for ($x = 0; $x < 12; $x++){

            $orderSuccess = Order::where('orders.status_order_id','=',4)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $x+1)->count();
            $orderCancel = Order::where('orders.status_order_id','=',5)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $x+1)->count();
            $idxData = array( $orderSuccess,$orderCancel);

            $response[$x] = $idxData;
        
        }

        return response()->json($response
        , 200);
    }
}
