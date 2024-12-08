<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductMovement;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Producto más vendido
        $mostSoldProduct = Product::withCount('movements')
            ->orderBy('movements_count', 'desc')
            ->first();

        // Productos con stock bajo (por ejemplo, con stock menor a 5)
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        // Últimos movimientos de productos (entradas y salidas)
        $latestMovements = ProductMovement::latest()->take(5)->get();

        return view('statistics.index', compact('mostSoldProduct', 'lowStockProducts', 'latestMovements'));
    }
}
