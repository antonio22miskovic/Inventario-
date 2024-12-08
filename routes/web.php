<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DateTable\ProductController as TableProduct;

use App\Http\Controllers\ProductMovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;

/*  
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
})->middleware('guest'); // solo accesible para invitados

Route::get('/register', function () {
    return redirect('/login');
})->middleware('guest'); // solo accesible para invitados

Auth::routes();

Route::middleware(['role:admin|user'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('products', ProductController::class)->middleware('role:admin');
    Route::get('datetable/product', [TableProduct::class, 'get'])->name('products.table');

    Route::resource('categories', CategoryController::class)->middleware('role:admin');
    Route::get('datetable/data', [CategoryController::class, 'getCategories'])->name('categories.data');


    Route::resource('product_movements', ProductMovementController::class);
    Route::get('datetable/product_movements/data', [ProductMovementController::class, 'getMovements'])->name('product_movements.data');

    Route::get('/statistics', [StatisticsController::class, 'index'])->middleware('role:admin')->name('statistics');

});

Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/roles', function() {
        return response()->json(App\Models\Role::all());  // Devolver todos los roles en formato JSON
    });

    // Ruta para mostrar el formulario de cambio de contraseña
Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->middleware('role:admin')->name('change-password');

// Ruta para procesar el cambio de contraseña
Route::post('/change-password', [UserController::class, 'changePassword'])->middleware('role:admin')->name('change-password.update');

});



