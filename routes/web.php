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

Route::get('/home', [HomeController::class, 'index'])->middleware('role:admin|user_stats|user_movements|user')->middleware('role:admin|user_stats|user_movements|user')->name('home');



Route::resource('product_movements', ProductMovementController::class)->middleware('role:admin|user_movements');
Route::get('datetable/product_movements/data', [ProductMovementController::class, 'getMovements'])->middleware('role:admin|user_movements')->name('product_movements.data');

    

Route::middleware('role:admin|user')->group(function () {

    Route::resource('products', ProductController::class);
    Route::get('datetable/product', [TableProduct::class, 'get'])->name('products.table');

});

Route::middleware('role:admin|user_stats')->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
});

Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/roles', function() {
        return response()->json(Spatie\Permission\Models\Role::orderBy('id','desc')->get());  // Devolver todos los roles en formato JSON
    });

    Route::resource('categories', CategoryController::class)->middleware('role:admin');
    Route::get('datetable/data', [CategoryController::class, 'getCategories'])->name('categories.data');

    // Ruta para mostrar el formulario de cambio de contraseña
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->middleware('role:admin')->name('change-password');

    // Ruta para procesar el cambio de contraseña
    Route::post('/change-password', [UserController::class, 'changePassword'])->middleware('role:admin')->name('change-password.update');

});



