<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
class ProductCategoryController extends Controller
{
    public function index()
    {
        $list = ProductCategory::all();
        
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
            
            $productCategory = new ProductCategory;
            $productCategory->name        = $request->get('name');
            $productCategory->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Product category created successfully',
                'data'      => $productCategory
            ], 201);
        }
    }


    public function show(string $id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        return response()->json([
            'status'    => true,
            'message'   => 'Product category found successfully',
            'data'      => $productCategory
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
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->name        = $request->get('name');
            $productCategory->save();

            return response()->json([
                'status'    => true,
                'message'   => 'Product category updated successfully',
                'data'      => $productCategory
            ], 200);
        }
    }

    public function destroy(string $id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        $productCategory->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Product category deleted successfully'
        ], 204);

    }
}
