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
        // $mostSoldProduct = Product::withSum(['movements as total_sales' => function ($query) {
        //     $query->where('movement_type', 'salida');
        // }], 'quantity')
        // ->orderBy('total_sales', 'desc')
        // ->first();

        $mostSoldProduct = ProductMovement::with('product') // Cargar el producto relacionado
            ->where('movement_type', 'salida') // Filtra solo las salidas
            ->get()
            ->groupBy('product_id') // Agrupar por el ID del producto
            ->map(function ($movimientosDelProducto) {
                return (object) [
                    'name' => $movimientosDelProducto->first()->product->name, // Acceder al nombre del producto
                    'movements_count' => $movimientosDelProducto->sum('quantity') // Sumar las cantidades
                ];
        });

        $mostSoldProduct = $mostSoldProduct->where('movements_count', $mostSoldProduct->max('movements_count'))->first();


        // Productos con stock bajo (por ejemplo, con stock menor a 5)
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        // Últimos movimientos de productos (entradas y salidas)
        $latestMovements = ProductMovement::latest()->take(5)->get();

        return view('statistics.index', compact('mostSoldProduct', 'lowStockProducts', 'latestMovements'));
    }
}
