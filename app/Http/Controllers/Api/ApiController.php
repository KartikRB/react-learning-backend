<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\SupportQuery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function support(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'type' => 'required|string|max:100',
            'issue_type' => 'exclude_unless:type,support|required|string|max:100',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ],[
            'issue_type.required_if' => "The issue type field is required."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $supportQuery = SupportQuery::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'issue_type' => $request->issue_type,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Your query has been submitted successfully!',
            'data' => $supportQuery
        ]);
    }

    public function getSupportQueries()
    {
        $data = SupportQuery::all();
        return response()->json([
            'status' => true,
            'message' => 'Data found!',
            'data' => $data
        ]);
    }

    public function getUsers()
    {
        $data = User::all();
        return response()->json([
            'status' => true,
            'message' => 'Data found!',
            'data' => $data
        ]);
    }

    public function getCategories($id = null)
    {
        $data = $id ? ProductCategory::find($id) : ProductCategory::all();
        return response()->json([
            'status' => true,
            'message' => 'Data found!',
            'data' => $data
        ]);
    }

    public function addProductCategory(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $id,
            'description' => 'required|string',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = [
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ];

        if ($request->hasFile('icon')) {
            $payload['icon'] = $request->file('icon')->store('categories', 'public');
        }

        if($id){
            $category = ProductCategory::find($id);
            if($category){
                $category->update($payload);
                $response = [
                    'status' => true,
                    'message' => 'Category updated successfully!',
                    'data' => $category
                ];
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Category not found!',
                ];
            }
        }else{
            $category = ProductCategory::create($payload);
            $response = [
                'status' => true,
                'message' => 'Category created successfully!',
                'data' => $category
            ];
        }

        return response()->json($response);
    }

    public function deleteProductCategory($id)
    {
        $category = ProductCategory::find($id);

        if($category){
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully!',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Category not found!',
        ]);
    }
}
