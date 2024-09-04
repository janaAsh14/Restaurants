<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $category = Category::all();
        return response()->json([

            "Category" => $category]);
    }

    public function store(Request $request){
        // Validate the Request
          $request->validate([
            'name' => 'required|string',
            'image' => 'required|string',
            'section_id' => 'required|integer',

        ]);

        // After validation, proceed with user creation
        $section = Category::create([
            'name' => $request->input('name'),
            'image' => $request->input('image'),
            'section_id' => $request->input('section_id'),
        ]);

        return response()->json([
            'message' => "Category Created Successfully"
        ]);

    }
    public function show( $id){
        $Category = Category::where('id', $id)->get();
        return response()->json([
            'Category' => $Category
        ]);
    }
    public function update( Request $request ,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|string',
        ]);

        $Category = Category::findOrFail($id);
        $Category->name = $request->name;
        $Category->image = $request->image;
        $Category->save();
        return response()->json([
            'result' => 'Book updated successfully',
            'Category' => $Category
    ]);

    }
    public function destroy(Category $Category){
        $Category = Category::find($Category->id);

        if(!$Category) {
            return response()->json(['message' => 'Book not found'], 404);
        }


        $Category->delete();
        return response()->json(['message' => 'Category Deleted Successfully'], 200);
    }
}
