<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserProfileController extends Controller
{
    public function getUser(Request $request){
        $user = User::where('role_id','=',3)->get();
        return response()->json($user, 200);
    }

    public function updateUser(Request $request){
        $user_id = $request->get('user_id');
        $user_name = $request->get('user_name');
        $user_phone = $request->get('user_phone');
        $user_email = $request->get('user_email');
        $user_address = $request->get('user_address');
        $user = User::findOrFail($user_id);
        $user->name =$user_name;
        $user->phone= $user_phone;
        $user->email=$user_email;
        $user->address=$user_address;
        $user->save();

        return response()->json(array($user), 201);
    }
    public function getUserProfile(Request $request){
        $user_id = $request->get("user_id");
        $user = User::find($user_id);
        return response()->json(array($user), 200);
    }

    public function updateUserProfile(Request $request){
        $user_id = $request->get("user_id");

        $user = User::find($user_id);

        

    }

    public function updateUserAddress(Request $request){
        $user_id = $request->get("user_id");

        $user = User::find($user_id);
        $user->address = $request->get("user_address");
        $user->save();
        
        return response()->json(array($user), 200);

    }

    public function courierProfile(Request $request){
        
    }
}
