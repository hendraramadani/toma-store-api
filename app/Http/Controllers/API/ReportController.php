<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\StatusOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    
    public function reportAllUser(Request $request){
        $public_storage = config('const.public_storage');

        $users = User::where('role_id','=',3)->get();
  
        $users = $users->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Akun Pengguna',
            'timestamp' => now(),
            'users' => $users
        ]; 
 
        $pdf = Pdf::loadView('account.pdf',$data);

        $filename = 'Laporan_akun_user_'.now()->format('Y-m-d_H-i-s').'.'.'pdf';
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());
        // return $pdf->download('itsolutionstuff.pdf');

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }

    public function reportAllCourier(Request $request){
        $public_storage = config('const.public_storage');

        $users = User::where('role_id','=',2)->get();
  
        $users = $users->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Akun Kurir',
            'timestamp' => now(),
            'users' => $users
        ]; 
 
        $pdf = Pdf::loadView('account.pdf',$data);
        
        $filename = 'Laporan_akun_kurir_'.now()->format('Y-m-d_H-i-s').'.'.'pdf';
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());
        // return $pdf->download('itsolutionstuff.pdf');

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }


    public function reportAllOrder(Request $request){
        $public_storage = config('const.public_storage');

        $result = Order::leftJoin('users as u','orders.user_id','=','u.id')
        ->leftJoin('couriers as c','orders.courier_id','=','c.id')
        ->leftJoin('users as cu','c.user_id','=','cu.id')
        ->leftJoin('status_orders as so','orders.status_order_id','=','so.id')
        ->select('orders.id as id','u.name as nama','cu.name as kurir', 'so.status','orders.total_cost as total', 'orders.created_at as dibuat')
        ->get();

        $result = $result->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Semua Order',
            'timestamp' => now(),
            'content' => $result
        ]; 
 
        $pdf = Pdf::loadView('order.pdf',$data);
        
        $filename = 'Laporan_akun_semua_order_'.now()->format('Y-m-d_H-i-s').'.'.'pdf';
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }


    public function recapAllSuccessOrder(Request $request){


        $public_storage = config('const.public_storage');

        $result = Order::where('orders.status_order_id','=',4)
        ->leftJoin('users as u','orders.user_id','=','u.id')
        ->leftJoin('couriers as c','orders.courier_id','=','c.id')
        ->leftJoin('users as cu','c.user_id','=','cu.id')
        ->leftJoin('status_orders as so','orders.status_order_id','=','so.id')
        ->select('orders.id as id','u.name as nama','cu.name as kurir', 'so.status','orders.total_cost as total', 'orders.created_at as dibuat')
        ->get();

        $orderCount = count($result);
        $sumCost = Order::where('orders.status_order_id','=',4)->sum('orders.total_cost');

        $result = $result->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Order Selesai',  ///changed
            'timestamp' => now(),
            'orderCount' => $orderCount,
            'sumCost' =>  $sumCost,
            'content' => $result
        ]; 
 
        $pdf = Pdf::loadView('order.success.pdf',$data); ///changed
        
        $filename = 'Laporan_rekap_sukses_order_'.now()->format('Y-m-d_H-i-s').'.'.'pdf'; ///changed
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }


    public function recapAllCancelledOrder(Request $request){

        $public_storage = config('const.public_storage');

        $result = Order::where('orders.status_order_id','=',5)
        ->leftJoin('users as u','orders.user_id','=','u.id')
        ->leftJoin('couriers as c','orders.courier_id','=','c.id')
        ->leftJoin('users as cu','c.user_id','=','cu.id')
        ->leftJoin('status_orders as so','orders.status_order_id','=','so.id')
        ->select('orders.id as id','u.name as nama','cu.name as kurir', 'so.status','orders.total_cost as total', 'orders.created_at as dibuat')
        ->get();
        $orderCount = count($result);

        $result = $result->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Order Dibatalkan',  ///changed
            'timestamp' => now(),
            'orderCount' => $orderCount,
            'content' => $result
        ]; 
 
        $pdf = Pdf::loadView('order.cancelled.pdf',$data); ///changed
        
        $filename = 'Laporan_rekap_dibatalkan_order_'.now()->format('Y-m-d_H-i-s').'.'.'pdf'; ///changed
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }


    public function recapByDateSuccessOrder(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $public_storage = config('const.public_storage');

        $result = Order::where('orders.status_order_id','=',4)
        ->whereBetween('orders.created_at', [$start_date, $end_date])
        ->leftJoin('users as u','orders.user_id','=','u.id')
        ->leftJoin('couriers as c','orders.courier_id','=','c.id')
        ->leftJoin('users as cu','c.user_id','=','cu.id')
        ->leftJoin('status_orders as so','orders.status_order_id','=','so.id')
        ->select('orders.id as id','u.name as nama','cu.name as kurir', 'so.status','orders.total_cost as total', 'orders.created_at as dibuat')
        ->get();

        $orderCount = count($result);
        $sumCost = Order::where('orders.status_order_id','=',4)->sum('orders.total_cost');

        $result = $result->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Pesanan Selesai Berdasarkan Tanggal', ///changed
            'start_date' => $start_date,
            'end_date' => $end_date,  
            'timestamp' => now(),
            'sumCost' => $sumCost,
            'orderCount' => $orderCount,
            'content' => $result
        ]; 
 
        $pdf = Pdf::loadView('order.date.success.pdf',$data); ///changed
        
        $filename = 'Laporan_rekap_date_order_sukses_'.now()->format('Y-m-d_H-i-s').'.'.'pdf'; ///changed
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }


    public function recapByDateCancelledOrder(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $public_storage = config('const.public_storage');

        $result = Order::where('orders.status_order_id','=',5)
        ->whereBetween('orders.created_at', [$start_date, $end_date])
        ->leftJoin('users as u','orders.user_id','=','u.id')
        ->leftJoin('couriers as c','orders.courier_id','=','c.id')
        ->leftJoin('users as cu','c.user_id','=','cu.id')
        ->leftJoin('status_orders as so','orders.status_order_id','=','so.id')
        ->select('orders.id as id','u.name as nama','cu.name as kurir', 'so.status','orders.total_cost as total', 'orders.created_at as dibuat')
        ->get();

        $orderCount = count($result);

        $result = $result->chunk(30);
  
        $data = [
            'title' => 'Laporan Data Pesanan Dibatalkan Berdasarkan Tanggal', ///changed
            'start_date' => $start_date,
            'end_date' => $end_date,  
            'timestamp' => now(),
            'orderCount' => $orderCount,
            'content' => $result
        ]; 
 
        $pdf = Pdf::loadView('order.date.cancelled.pdf',$data); ///changed
        
        $filename = 'Laporan_rekap_date_order_dibatalkan_'.now()->format('Y-m-d_H-i-s').'.'.'pdf'; ///changed
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }

    public function reportAllStore(Request $request){
        $public_storage = config('const.public_storage');

        $store = Store::all();
  
        $store = $store->chunk(30);
        
        $storeCount = count($store);
        $data = [
            'title' => 'Laporan Data Semua Toko',
            'timestamp' => now(),
            'storeCount' => $storeCount,
            'content' => $store
        ]; 
 
        $pdf = Pdf::loadView('store.pdf',$data);

        $filename = 'Laporan_semua_toko_'.now()->format('Y-m-d_H-i-s').'.'.'pdf';
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }

    public function reportAllProduct(Request $request){
        $public_storage = config('const.public_storage');

        $product = Product::leftJoin('stores','products.store_id','=','stores.id')
        ->select('products.*','stores.name as store_name')
        ->get();

        $product = $product->chunk(30);
        
        $productCount = count($product);
        $data = [
            'title' => 'Laporan Data Semua Produk',
            'timestamp' => now(),
            'productCount' => $productCount,
            'content' => $product
        ]; 
 
        $pdf = Pdf::loadView('product.pdf',$data);

        $filename = 'Laporan_semua_produk_'.now()->format('Y-m-d_H-i-s').'.'.'pdf';
        $imageFolder='report/';
        Storage::disk('public')->put($imageFolder.$filename, $pdf->download());

        $response = array([
            'file_name'=> $filename,
            'file_path'=>$public_storage.'/'.$imageFolder.$filename]);
        return response($response,200);
    }
}
