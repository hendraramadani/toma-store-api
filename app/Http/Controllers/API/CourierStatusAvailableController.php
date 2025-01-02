<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourierStatusAvailable;
use Illuminate\Support\Facades\Validator;
class CourierStatusAvailableController extends Controller
{
    public function index()
    {
        $list = CourierStatusAvailable::all();
        
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
            
            $courierStatusAvailable = new CourierStatusAvailable;
            $courierStatusAvailable->status        = $request->get('status');
            $courierStatusAvailable->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer created successfully',
                'data'      => $courierStatusAvailable
            ], 201);
        }
    }


    public function show(string $id)
    {
        $courierStatusAvailable = CourierStatusAvailable::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Customer found successfully',
            'data'      => $courierStatusAvailable
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
            $courierStatusAvailable = CourierStatusAvailable::findOrFail($id);
            $courierStatusAvailable->status        = $request->get('status');
            $courierStatusAvailable->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer updated successfully',
                'data'      => $courierStatusAvailable
            ], 200);
        }
    }

    public function destroy(string $id)
    {
        $courierStatusAvailable = CourierStatusAvailable::findOrFail($id);
        $courierStatusAvailable->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Customer deleted successfully'
        ], 204);

    }
}
