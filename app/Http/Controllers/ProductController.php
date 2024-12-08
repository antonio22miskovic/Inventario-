<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
     // Mostrar los productos
     public function index()
     {  
        return view('products.index', [
           'categories' => Category::orderBy('id', 'desc')->get()
        ]);
     }

    public function show (Product $product) {
       return $product;
    }
    
    public function getProducts () {
        $products = Product::orderBy('id', 'desc')->with('category')->get();
        return DataTables::of($products)
            ->addColumn('category', function($product) {
                return $product->category->name;
            })
            ->addColumn('action', function($product) {
                return '<button class="btn btn-warning btn-sm" onclick="editProduct(' . $product->id . ')">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(' . $product->id . ')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    // Mostrar formulario de creación
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Crear nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($request->all());

        return response()->json(['success' => 'Producto creado correctamente']);
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json(['success' => 'Producto actualizado correctamente']);
    }

    // Eliminar producto
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => 'Producto eliminado correctamente']);
    }
}
