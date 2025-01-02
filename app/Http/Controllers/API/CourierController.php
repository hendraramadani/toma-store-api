<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderDetail;
class CourierController extends Controller
{
    public function index(){
        $response = Courier::leftJoin('users','couriers.user_id','=','users.id')
        ->select('couriers.*','users.name as courier_name','users.phone as courier_phone','users.email as courier_email')
        ->get();

        return response()->json($response);

    }


    public function updateCourierData(Request $request){
        $user_id = $request->get('user_id');
        $courier_id = $request->get('courier_id');
        $status_available_id = $request->get('status_available_id');
        $courier_name = $request->get('courier_name');
        $courier_phone = $request->get('courier_phone');
        $courier_email = $request->get('courier_email');

        $courier =  Courier::findOrFail($courier_id);
        $courier->courier_status_active_id = $status_available_id;
        $courier->save();

        $user =  User::findOrFail( $user_id);
        $user->name = $courier_name;
        $user->phone = $courier_phone;
        $user->email = $courier_email;
        $user->save();

        $courier->courier_name =  $user->name;
        $courier->courier_phone =  $user->phone;
        $courier->courier_email =  $user->email;

        return response()->json(array($courier),201);
    }
    public function getCourierData(Request $request)
    {
        $id = $request->get("user_id");
        $response = User::where('id', '=', $id)
        ->get();

        $status = Courier::where('user_id', '=', $response[0]->id)
        ->leftJoin('courier_status_actives', 'couriers.courier_status_active_id', '=', 'courier_status_actives.id')
        ->leftJoin('courier_status_availables', 'couriers.courier_status_available_id', '=', 'courier_status_availables.id')
        ->select('couriers.*', 'courier_status_actives.status as courier_status_actives_name','courier_status_availables.status as courier_status_availables_name')
        ->get();

        $response[0]->status = $status;
        return response()->json($response);
    }


    public function getAllListOrderCourier(){
        $response = array();
        $public_storage = config('const.public_storage');


        $order = Order::where('courier_id', '=', null)->where('status_order_id','=',value: 3)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->orderBy('orders.id', 'asc')
        ->select('orders.*', 'users.name','users.address','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->select('orders_detail.*', 'products.name as product_name', 'stores.name as store_name',
                    DB::raw("CONCAT('$public_storage',`products`.`image`)  AS product_image"))
                    ->orderBy('store_name', 'desc')
            ->get();
            $response[$key]['order'] =  $index;
            $response[$key]['detail'] =  $orderdetail;
            
        }

        
        return response()->json($response
        , 200);
    }


    public function assignOrderCourier(Request $request){
        $courier_id = $request->get('courier_id');
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->courier_id = (int)$courier_id;
        $response->status_order_id = 1;
        $response->save();


        return response()->json($response
        , 201);
    }


    public function takenListOrderCourier(Request $request){    
        $courier_id = $request->get('courier_id');

        $response = array();
        $public_storage = config('const.public_storage');


        $order = Order::where('courier_id', '=', $courier_id)->where('status_order_id','!=',value: 4)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->orderBy('orders.id', 'asc')
        ->select('orders.*', 'users.name','users.address','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->select('orders_detail.*', 'products.name as product_name', 'stores.name as store_name',
                    DB::raw("CONCAT('$public_storage',`products`.`image`)  AS product_image"))
                    ->orderBy('store_name', 'desc')
            ->get();
            $response[$key]['order'] =  $index;
            $response[$key]['detail'] =  $orderdetail;
            
        }

        
        return response()->json($response
        , 200);
    }


    public function deliverOrderCourier(Request $request){
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->status_order_id = 2;
        $response->save();


        return response()->json($response
        , 201);
    }

    public function cancelOrderCourier(Request $request){
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->courier_id = null;
        $response->status_order_id = 3;
        $response->save();


        return response()->json($response
        , 201);
    }

    public function doneListOrderCourier(Request $request){    
        $courier_id = $request->get('courier_id');

        $response = array();
        $public_storage = config('const.public_storage');


        $order = Order::where('courier_id', '=', $courier_id)->where('status_order_id','=',value: 4)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->orderBy('orders.id', 'asc')
        ->select('orders.*', 'users.name','users.address','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->select('orders_detail.*', 'products.name as product_name', 'stores.name as store_name',
                    DB::raw("CONCAT('$public_storage',`products`.`image`)  AS product_image"))
                    ->orderBy('store_name', 'desc')
            ->get();
            $response[$key]['order'] =  $index;
            $response[$key]['detail'] =  $orderdetail;
            
        }

        
        return response()->json($response
        , 200);
    }

    public function updateStatusAvailableCourier(Request $request){
        $user_id = $request->get('user_id');
        $status_id = $request->get('courier_status_available_id');

        $response = Courier::where('user_id','=',$user_id)->update(['courier_status_available_id' =>  (int)$status_id]);   


        return response()->json($response
        , 201);
    }
}
