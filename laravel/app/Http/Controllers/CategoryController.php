<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
        // --- Get /api/categories
        public function getCategories(){
            $categories = Category::all(); // Fetch only the categories without any relations
        
            return response()->json($categories);
        }
        // --- Post /api/categories
        public function createCategory(Request $request){

            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create(['name' => $request->name]);

            return response()->json([
            "message" => "Category created successfully",
            "category" => $category
            ], 201);
        }   

                // --- Get /api/categories/{categoryId}
        public function getCategory($categoryId){
            // Fetch the category by ID or return a 404 if not found
            $category = Category::find($categoryId);

            if (!$category) {
                return response()->json(["message" => "Category not found"], 404);
            }

            return response()->json($category);
        }

        // --- Patch /api/categories/{categoryId}
        public function updateCategory(Request $request, $categoryId){
            // Fetch the category by ID or return a 404 if not found
            $category = Category::find($categoryId);
    
            if (!$category) {
                return response()->json(["message" => "Category not found"], 404);
            }

            $request->validate(['name' => 'required|string|max:255',]);
    
            // Update the category
            $category->update(['name' => $request->name]);
    
            return response()->json([
                "message" => "Category updated successfully",
                "category" => $category
            ]);
        }

        // --- Delete /api/categories/{categoryId}
        public function deleteCategory($categoryId){
            // Fetch the category by ID or return a 404 if not found
            $category = Category::find($categoryId);

            if (!$category) {
                return response()->json(["message" => "Category not found"], 404);
            }

            // Delete the category
            $category->delete();

            return response()->json(["message" => "Category deleted successfully"]);
        }
}
