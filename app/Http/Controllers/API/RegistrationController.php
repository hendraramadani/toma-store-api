<?php
   
   namespace App\Http\Controllers\Api;
   use App\Http\Controllers\Controller;
   use App\Models\User;
   use App\Models\Courier;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Facades\Validator;
   use Illuminate\Validation\ValidationException;
   
class RegistrationController extends Controller
{
    public function register(Request $request)
    {

        $rules = array(
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'phone'     => 'required|string|max:255',
            'password'  => 'required|string|min:8|confirmed',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            // dd($request);
            
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'role_id'   => $request->role_id,
                'password'  => Hash::make($request->password),
            ]);

            if($request->role_id == 2){
                // dd($user['id']);

                $courier_info = Courier::create([
                    'user_id'                           => $user['id'],
                    'courier_status_active_id'          => 1,
                    'courier_status_available_id'       => 3,
                ]);
                $data = array(
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'role_id' => (int)$user['role_id'],
                );
                $responsBody = array([
                    'success' => true,
                    'msg' => 'Login Success',
                    'access_token' => 'none',
                    'token_type' => 'none',
                    'data' => $data,
                ]);

                return response()->json($responsBody,201);
            }else{
                $data = array(
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'role_id' => (int)$user['role_id'],
                );

                $responsBody = array([
                    'success' => true,
                    'msg' => 'Login Success',
                    'access_token' => 'none',
                    'token_type' => 'none',
                    'data' => $data,
                ]);
                return response()->json($responsBody,201);
            }
            
            
        }
    }
}
