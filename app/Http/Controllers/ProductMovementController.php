<?php

namespace App\Http\Controllers;

use App\Models\ProductMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductMovementController extends Controller
{
    public function index()
    {   

        return view('product_movements.index',[
            'products' => Product::orderBy('id', 'desc')->get()
        ]);
    }

    public function getMovements(Request $request)
    {
        $movements = ProductMovement::with('product')->get();
        return DataTables::of($movements)
            ->addColumn('action', function ($movement) {
                return '<button class="btn btn-danger btn-sm" onclick="deleteMovement(' . $movement->id . ')"> <i class="fas fa-trash"></i> Eliminar</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'movement_type' => 'required|in:entrada,salida',
        ]);
    
        // Obtenemos el producto
        $product = Product::findOrFail($request->product_id);
    
        // Si es un movimiento de salida, verificamos que haya suficiente stock
        if ($request->movement_type === 'salida') {
            // Sumamos todas las salidas previas de este producto
            $totalSalidas = $product->movements()->where('movement_type', 'salida')->sum('quantity');
            
            // Calculamos el stock disponible
            $stockDisponible = $product->stock - $totalSalidas;
    
            // Si la cantidad solicitada supera el stock disponible, retornamos un error
            if ($request->quantity > $stockDisponible) {
                return response()->json(['error' => 'No hay suficiente stock disponible para esta salida.'], 400);
            }
        }
    
        // Registramos el movimiento
        ProductMovement::create($request->all());
        
        // Actualizamos el stock del producto
        if ($request->movement_type === 'entrada') {
            $product->stock += $request->quantity;
        } else {
            $product->stock -= $request->quantity;
        }
        $product->save();
    
        return response()->json(['message' => 'Movimiento registrado con éxito']);
    }
    

    public function destroy($id)
    {
        $movement = ProductMovement::findOrFail($id);
        $movement->delete();
        return response()->json(['message' => 'Movimiento eliminado con éxito']);
    }
}
