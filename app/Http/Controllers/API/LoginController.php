<?php
   
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
   
class LoginController extends Controller
{
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            $data = array(
                'id' => -1,
                'name' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
                'role_id' =>-1,
            );
            $responsBody = array([
                'success' => false,
                'msg' => 'Login Failed',
                'access_token' => '',
                'token_type' => '',
                'data' => $data,
            ]);
            return response()->json( $responsBody,401);
        }else{
            $data = array(
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'role_id' => $user['role_id'],
            );
            $token = $user->createToken('auth_token')->plainTextToken;
            $responsBody = array([
                'success' => true,
                'msg' => 'Login Success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $data,
            ]);
            return response()->json($responsBody,200);
        }


    }
}