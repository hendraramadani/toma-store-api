<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemporaryCart;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class TemporaryCartController extends Controller
{

    public function getCart(Request $request){
        $cart = TemporaryCart::where('user_id', '=', $request->get('user_id'))->first();
        if($cart !=null ){
            return response()->json(json_decode($cart->data), 200);
        }else{
            return response()->json(array(), 200);
        }

    }
    public function cartConstructor(Request $request)
    {
        // dd($request->get('image'));
        $rules = array(
            'user_id'      => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            $cart = TemporaryCart::where('user_id', '=', $request->get('user_id'))->first(); 
            // dd($check);
            // dd(json_encode($cart->data));
            if($cart !=null ){
                $cart->data       = $request->get('data');
                $cart->save();
            
    
                return response()->json(json_decode($cart->data), 200);
            }else{
                $cart = new TemporaryCart;
                $cart->user_id        = $request->get('user_id');
                $cart->data       = $request->get('data');
                $cart->save();
                
    
                return response()->json(json_decode($cart->data), 201);
            }


            
        }
    }
}
