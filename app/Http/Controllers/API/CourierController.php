<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\StatusOrder;

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
        ->orderBy('orders.id', 'desc')
        ->select('orders.*', 'users.name','users.address','users.phone as user_phone','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->orderBy('stores.id', 'asc')
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

        $userData = User::where('users.id','=',$response->user_id)->get();
        // dd($userData[0]['phone']);
        $courierData = Courier::where('couriers.id','=',$courier_id)
        ->leftJoin('users','couriers.user_id','=','users.id')
        ->select('users.name','users.phone')
        ->get();
        $statusData = StatusOrder::where('id','=',1)->get();
        
        $dest_phone= $userData[0]['phone'];
        $invoice_id = $response->id;
        $courier_name=$courierData[0]['name'];
        $courier_phone=$courierData[0]['phone'];
        $status_pesanan=$statusData[0]['status'];
        $total_harga = $response->total_cost;

        
        $this->send_whatsapp_notification_from_admin($dest_phone,$invoice_id,
        $courier_name,$courier_phone,$status_pesanan,$total_harga);


        return response()->json($courierData
        , 201);
    }


    public function takenListOrderCourier(Request $request){    
        $courier_id = $request->get('courier_id');

        $response = array();
        $public_storage = config('const.public_storage');


        $order = Order::where('courier_id', '=', $courier_id)->where('status_order_id','!=',value: 4)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
        ->orderBy('orders.id', 'desc')
        ->select('orders.*', 'users.name','users.address','users.phone as user_phone','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->orderBy('stores.id', 'asc')
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
        $courier_id = $request->get('courier_id');
        

        $response = Order::findOrFail($order_id);
        $response->status_order_id = 2;
        $response->save();

        $userData = User::where('users.id','=',$response->user_id)->get();
        // dd($userData[0]['phone']);
        $courierData = Courier::where('couriers.id','=',$courier_id)
        ->leftJoin('users','couriers.user_id','=','users.id')
        ->select('users.name','users.phone')
        ->get();
        $statusData = StatusOrder::where('id','=',2)->get();
        
        $dest_phone= $userData[0]['phone'];
        $invoice_id = $response->id;
        $courier_name=$courierData[0]['name'];
        $courier_phone=$courierData[0]['phone'];
        $status_pesanan=$statusData[0]['status'];
        $total_harga = $response->total_cost;

        
        $this->send_whatsapp_notification_from_admin($dest_phone,$invoice_id,
        $courier_name,$courier_phone,$status_pesanan,$total_harga);


        return response()->json($response
        , 201);
    }

    public function cancelOrderCourier(Request $request){
        $order_id = $request->get('order_id');

        $response = Order::findOrFail($order_id);
        $response->courier_id = null;
        $response->status_order_id = 3;
        $response->save();

        $userData = User::where('users.id','=',$response->user_id)->get();
        // dd($userData[0]['phone']);

        $statusData = StatusOrder::where('id','=',5)->get();
        
        $dest_phone= $userData[0]['phone'];
        $invoice_id = $response->id;
        $status_pesanan=$statusData[0]['status'];

        $this->send_whatsapp_notification_from_admin_delete($dest_phone,$invoice_id,$status_pesanan);

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
        ->orderBy('orders.id', 'desc')
        ->select('orders.*', 'users.name','users.address','users.phone as user_phone','status_orders.status')
        ->get();


        foreach( $order as $key=>$index) {
            $orderdetail = OrderDetail::where('orders_id', '=', $index->id)
            ->leftJoin('products', 'orders_detail.product_id', '=', 'products.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->orderBy('stores.id', 'asc')
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


    public function send_whatsapp_notification_from_admin($dest_phone,$invoice_id,$courier_name,$courier_phone,$status_pesanan,$total_harga){

        // $token = config('const.whatsapp_apikey');
        $token = "7PVRqfihfZpNwZgeMFVb";
        $target = $dest_phone; //dinamis


        $msg = '*NOTIFIKASI PENGANTARAN PESANAN TOMA STORE*'.'

*Invoice* #'.$invoice_id.'  '.'
    
*Status Pesanan :* '.$status_pesanan.'  '.'
*Kurir :* '.$courier_name.'  '.'( '.$courier_phone.' )'.'

*Total Harga :* '.$total_harga.'  '.'
    
_Pesan ini dikirim dari sistem admin. Balas pesan ini hanya jika ada pertanyan !_'
    ;
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
        'target' => $target,
        'message' => $msg, ///dinamis alamat yang diadu
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        return $response;
    
        }


    public function send_whatsapp_notification_from_admin_delete($dest_phone,$invoice_id,$status_pesanan){

        // $token = config('const.whatsapp_apikey');
        $token = "7PVRqfihfZpNwZgeMFVb";
        $target = $dest_phone; //dinamis


        $msg = '*NOTIFIKASI PENGANTARAN PESANAN TOMA STORE*'.'

*Invoice* #'.$invoice_id.'  '.'
    
*Status Pesanan :* '.$status_pesanan.'  Kurir'.'
   
Pesanan dibatalkan kurir, silahkan tunggu kurir selanjutnya mengambil pesanan anda.

_Pesan ini dikirim dari sistem admin. Balas pesan ini hanya jika ada pertanyan !_'
    ;
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
        'target' => $target,
        'message' => $msg, ///dinamis alamat yang diadu
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        return $response;
    
        }
}
