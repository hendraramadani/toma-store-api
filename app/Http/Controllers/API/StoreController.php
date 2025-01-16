<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index()
    {
        $public_storage = config('const.public_storage');
        $list = Store::where('deleted_at','=',null)->
        select('stores.*',DB::raw("CONCAT('$public_storage',`stores`.`image`)  AS image"))
        ->get();
        
        return response()->json($list);
        
    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        // dd($request->get('image'));
        $rules = array(
            'name'      => 'required|string|max:255',
            'phone'     => 'required|numeric',
            'address'   => 'required|string',
            'image'     => 'required',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imagePath = 'store'. '/' .Str::random(length: 40).'.'.'png';
            $image = base64_decode($image);
            $path = Storage::disk('public')->put($imagePath, $image);
            
            $store = new Store;
            $store->name        = $request->get('name');
            $store->phone       = $request->get('phone');
            $store->address     = $request->get('address');
            $store->image       = '/'.$imagePath;
            $store->latitude    = $request->get('latitude');
            $store->longitude   = $request->get('longitude');
            $store->save();

            $response = array([ 
                'status'    => true,
                'message'   => 'Store created successfully',
                'data'      => $store]);

            return response()->json(array($store), 201);
        }
    }


    public function show(string $id)
    {
        $store = Store::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Customer found successfully',
            'data'      => $store
        ], 201);
    }


    public function update(Request $request, string $id)
    {
        $public_storage = config('const.public_storage');
        $rules = array(
            'name'      => 'required|string|max:255',
            'phone'     => 'required|numeric',
            'address'   => 'required|string',
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            // store
            $image = $request->get('image');
            $store = Store::findOrFail($id);
            $store->name        = $request->get('name');
            $store->phone       = $request->get('phone');
            $store->address     = $request->get('address');

            if($image!=null){
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imagePath = 'store'. '/' .Str::random(length: 40).'.'.'png';
                $image = base64_decode($image);
                $path = Storage::disk('public')->put($imagePath, $image);  
                $store->image                 = '/'.$imagePath;
            }
             
            $store->save();
            if($image!=null){
                $store->image = $public_storage.'/'.$imagePath;
            }
            return response()->json(array($store), 201);
        }
    }

    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
        $store->deleted_at = now();
        $store->save();

        Product::where('store_id', '=', $id)->update(['deleted_at' => now()]);
        
        return response()->json([
            'status'    => true,
            'message'   => 'Customer deleted successfully'
        ], 204);

    }
}
