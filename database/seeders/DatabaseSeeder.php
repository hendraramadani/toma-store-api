<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        DB::table("roles")->insert(['name'=>'Admin', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("roles")->insert(['name'=>'Courier', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("roles")->insert(['name'=>'User', 'created_at'=>now(), 'updated_at'=>now()]);

        DB::table("product_categories")->insert(['name'=>'Makanan', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("product_categories")->insert(['name'=>'Minuman', 'created_at'=>now(), 'updated_at'=>now()]);

        DB::table("users")->insert(['name'=>'Admin', 'email'=>'admin@mail.com', 'phone'=>'081230275840','password'=>Hash::make('admin'),'role_id'=>'1', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("users")->insert(['name'=>'Courier', 'email'=>'courier@mail.com', 'phone'=>'081230275840','password'=>Hash::make('courier'),'role_id'=>'2', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("users")->insert(['name'=>'User', 'email'=>'user@mail.com', 'phone'=>'081230275840','password'=>Hash::make('user'),'role_id'=>'3', 'created_at'=>now(), 'updated_at'=>now()]);

        DB::table("courier_status_actives")->insert(['status'=>'Aktif', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("courier_status_actives")->insert(['status'=>'Non-Aktif', 'created_at'=>now(), 'updated_at'=>now()]);

        DB::table("courier_status_availables")->insert(['status'=>'Tersedia', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("courier_status_availables")->insert(['status'=>'Mengantar', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("courier_status_availables")->insert(['status'=>'Off', 'created_at'=>now(), 'updated_at'=>now()]);
    
        DB::table("status_orders")->insert(['status'=>'Pesanan Dijemput Kurir', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("status_orders")->insert(['status'=>'Pesanan Diantar Kurir', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("status_orders")->insert(['status'=>'Menunggu Kurir', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("status_orders")->insert(['status'=>'Pesanan Selesai', 'created_at'=>now(), 'updated_at'=>now()]);
        DB::table("status_orders")->insert(['status'=>'Pesanan Dibatalkan', 'created_at'=>now(), 'updated_at'=>now()]);

        DB::table("stores")->insert(['name'=>'Hendra Store', 'phone'=>'081230275840','address'=>'Kalangbret','latitude'=>'-8.049328737865013','longitude'=>'111.86482451286015', 'created_at'=>now(), 'updated_at'=>now()]);
    
        DB::table("couriers")->insert(['user_id'=>2,'courier_status_active_id'=>1,'courier_status_available_id'=>3, 'created_at'=>now(), 'updated_at'=>now()]);
    }
}
