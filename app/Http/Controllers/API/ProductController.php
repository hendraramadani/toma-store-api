<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $public_storage = config('const.public_storage');
        
        $list = Product::where('products.deleted_at','=',null)->leftJoin('product_categories','products.product_categorie_id','=','product_categories.id')
        ->leftJoin('stores','products.store_id','=','stores.id')
        ->select('products.*', DB::raw("CONCAT('$public_storage',`products`.`image`)  AS image"),'product_categories.name as product_categorie_name', 'stores.name as store_name')
        ->get();
        
        return response()->json($list,200);
    }


    

   public function getProductFromuser(){

    $response = DB::select( "SELECT `A`.*,CONCAT(:public_storage,`A`.`image`) AS `image_url`, `B`.`name` AS `product_categorie_name`, `C`.`name` AS `store_name` FROM `products` `A`
                                    LEFT OUTER JOIN `product_categories` `B` ON (`A`.`product_categorie_id` = `B`.`id`)
                                    LEFT OUTER JOIN `stores` `C` ON (`A`.`store_id` = `C`.`id`)
                                    WHERE (`B`.`id` IS NOT NULL OR `C`.`id` IS NOT NULL) AND `A`.`deleted_at` IS NULL;"
                               , array(
                                'public_storage' => config('const.public_storage')) 
                            );

    
    //  $a = array([
    //     "name" => $response->name
    //  ]);                           

    return response()->json($response, 200);
   }
    public function store(Request $request)
    {
        $rules = array(
            'name'                  => 'required|string|max:255',
            'stock'                 => 'required|numeric|max:255',
            'description'           => 'required|string|max:255',
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
            $imagePath = 'product'. '/' .Str::random(length: 40).'.'.'png';
            $image = base64_decode($image);
            $path = Storage::disk('public')->put($imagePath, $image);
            // dd($path);
            $product = new Product;
            $product->name                  = $request->get('name');
            $product->stock                 = preg_replace('/[^0-9]/', '', $request->get('stock'));
            $product->description           = $request->get('description');
            $product->cost                  = (int)preg_replace('/[^0-9]/', '', $request->get('cost'));
            $product->product_categorie_id  = (int)$request->get('product_categorie_id');
            $product->image                 = '/'.$imagePath;
            $product->store_id              = (int)$request->get('store_id');
            $product->save();

            $response = array(['status'    => true,
                'message'   => 'Product created successfully',
                'data'      => $product]);

            return response()->json($response, 201);
        }
    }


    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Product found successfully',
            'data'      => $product
        ], 200);
    }


    public function update(Request $request, string $id)
    {
            $public_storage = config('const.public_storage');
            $image = $request->get('image');
            $product = Product::findOrFail($id);
            $product->name                  = $request->get('name');
            $product->stock                 = (int)preg_replace('/[^0-9]/', '', $request->get('stock'));
            $product->description           = $request->get('description');
            $product->cost                  = (int)preg_replace('/[^0-9]/', '', $request->get('cost'));
            $product->store_id              = $request->get('store_id');
            $product->product_categorie_id  = $request->get('product_categorie_id');
            if($image!=null){
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imagePath = 'product'. '/' .Str::random(length: 40).'.'.'png';
                $image = base64_decode($image);
                $path = Storage::disk('public')->put($imagePath, $image);  
                $product->image                 = '/'.$imagePath;
            }

            $product->save();
            if($image!=null){
                $product->image = $public_storage.'/'.$imagePath;
            }
            return response()->json(array($product), 201);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->deleted_at = now();
        $product->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Product deleted successfully'
        ], 204);

    }
}
