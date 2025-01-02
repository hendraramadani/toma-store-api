<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourierStatusActive;
use Illuminate\Support\Facades\Validator;

class CourierStatusActiveController extends Controller
{
    public function index()
    {
        $list = CourierStatusActive::all();
        
        return response()->json($list);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        
        $rules = array(
            'status'      => 'required|string|max:255',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => false,
                'message'   => 'Validation error',
                'errors'    => $validator->errors()
            ], 422);

        } else {
            
            $courierStatusActive = new CourierStatusActive;
            $courierStatusActive->status        = $request->get('status');
            $courierStatusActive->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer created successfully',
                'data'      => $courierStatusActive
            ], 201);
        }
    }


    public function show(string $id)
    {
        $courierStatusActive = CourierStatusActive::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Customer found successfully',
            'data'      => $courierStatusActive
        ], 200);
    }


    public function update(Request $request, string $id)
    {
        $rules = array(
            'status'      => 'required|string|max:255',
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
            $courierStatusActive = CourierStatusActive::findOrFail($id);
            $courierStatusActive->status        = $request->get('status');
            $courierStatusActive->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer updated successfully',
                'data'      => $courierStatusActive
            ], 200);
        }
    }

    public function destroy(string $id)
    {
        $courierStatusActive = CourierStatusActive::findOrFail($id);
        $courierStatusActive->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Customer deleted successfully'
        ], 204);

    }
}
