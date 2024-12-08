<?php

namespace App\Http\Controllers\DateTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function get (Request  $request) {
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
}
