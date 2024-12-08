<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Yajra\DataTables\DataTables;



class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function getCategories(Request $request)
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return DataTables::of($categories)
            ->addColumn('action', function ($category) {
                return '<button class="btn btn-warning btn-sm" onclick="editCategory(' . $category->id . ')"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteCategory(' . $category->id . ')"> <i class="fas fa-trash"></i> Eliminar</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());
        return response()->json(['message' => 'Categoría creada con éxito']);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());
        return response()->json(['message' => 'Categoría actualizada con éxito']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada con éxito']);
    }
}
