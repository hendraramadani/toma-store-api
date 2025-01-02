<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    
    public function index()
    {
        $list = Role::all();
        
        return response()->json($list);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        
        $rules = array(
            'name'      => 'required|string|max:255',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            
            $role = new Role;
            $role->name        = $request->get('name');
            $role->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer created successfully',
                'data'      => $role
            ], 201);
        }
    }


    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Customer found successfully',
            'data'      => $role
        ], 200);
    }


    public function update(Request $request, string $id)
    {
        $rules = array(
            'name'      => 'required|string|max:255',
        );
        // dd($request);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            // store
            $role = Role::findOrFail($id);
            $role->name        = $request->get('name');
            $role->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer updated successfully',
                'data'      => $role
            ], 200);
        }
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Customer deleted successfully'
        ], 204);

    }
}
