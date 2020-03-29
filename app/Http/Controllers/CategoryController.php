<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::when($request->q, function($categories) use($request) {
            $categories->where('name', 'LIKE', '%' . $request->q . '%');
        })->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:categories,name',
            'description' => 'nullable|string|max:150'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return response()->json(['status' => 'success']);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json(['status' => 'success', 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:150'
        ]);

        $category = Category::find($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json(['status' => 'success']);
    }
}
