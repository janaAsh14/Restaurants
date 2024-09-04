<?php

namespace App\Http\Controllers;
use App\Models\Section;
use Illuminate\Http\Request;
use Random\Engine\Secure;

class SectionController extends Controller
{
    public function index() {
        $sections = Section::all();
        return response()->json([

            "sections" => $sections]);
    }

    public function store(Request $request){
        // Validate the Request
          $request->validate([
            'name' => 'required|string',
            'image' => 'required|string',

        ]);

        // After validation, proceed with user creation
        $product = Section::create([
            'name' => $request->input('name'),
            'image' => $request->input('image'),
        ]);

        return response()->json([
            'message' => "Section Created Successfully"
        ]);

    }
    public function update( Request $request ,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|string',
        ]);

        $section = Section::findOrFail($id);
        $section->name = $request->name;
        $section->image = $request->image;
        $section->save();
        return response()->json([
            'result' => 'Book updated successfully',
            'section' => $section
    ]);

    }
    public function destroy(Section $section){
        $section = Section::find($section->id);

        if(!$section) {
            return response()->json(['message' => 'Book not found'], 404);
        }


        $section->delete();
        return response()->json(['message' => 'Section Deleted Successfully'], 200);
    }
}
