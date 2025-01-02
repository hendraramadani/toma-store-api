<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Statusorder;
use Illuminate\Support\Facades\Validator;
class StatusOrderController extends Controller
{
    public function index()
    {
        $list = Statusorder::all();
        
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
            
            $statusorder = new Statusorder;
            $statusorder->status        = $request->get('status');
            $statusorder->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer created successfully',
                'data'      => $statusorder
            ], 201);
        }
    }


    public function show(string $id)
    {
        $statusorder = Statusorder::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Customer found successfully',
            'data'      => $statusorder
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
            $statusorder = Statusorder::findOrFail($id);
            $statusorder->status        = $request->get('status');
            $statusorder->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Customer updated successfully',
                'data'      => $statusorder
            ], 200);
        }
    }

    public function destroy(string $id)
    {
        $statusorder = Statusorder::findOrFail($id);
        $statusorder->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Customer deleted successfully'
        ], 204);

    }
}
