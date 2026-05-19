<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
  CategoryController,
  CashController,
  DashboardController,
  ProductController,
  MenuController,
  PasswordController,
  ProfileController,
  StatusController,
  LogsController,
  UserController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//404
Route::fallback(function() {
    return view('404');
});
// Route pour les utilisateurs
Route::controller(UserController::class)->group(function () {
  Route::get('/', 'login')->name('login');
  Route::post('/auth', 'auth');
});
// Routes pour les mots de passe oubliés
Route::controller(PasswordController::class)->group(function () {
  Route::get('/forgotpass', 'index');
  Route::post('/forgotpass', 'store');
});
// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
  Route::resources([
    'cashs' => CashController::class,
    'category' => CategoryController::class,
    'menus' => MenuController::class,
    'products' => ProductController::class,
    'profiles' => ProfileController::class,
    'users' => UserController::class,
  ]);
  // Routes pour Tableau de bord
  Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index');
    Route::post('/dashboard', 'statistics');
  });
  // Route pour Caisse
  Route::post('/reject', [CashController::class, 'reject']);
  // Routes pour les produits
  Route::controller(ProductController::class)->group(function () {
    Route::get('/gestStock', 'gestStock');
    Route::get('/getProduct/{id}', 'getProduct');
  });
  // Route pour liste des catégories
  Route::get('/getCategory/{id}', [CategoryController::class, 'getCategory']);
  // Route pour les utilisateurs
  Route::controller(UserController::class)->group(function () {
    Route::get('/account', 'account');
    Route::get('/logout', 'logout');
  });
  // Routes pour les mots de passe
  Route::controller(PasswordController::class)->group(function () {
    Route::get('/password', 'edit');
    Route::put('/password', 'update');
  });
  // Route pour les statuts
  Route::patch('/{type}/status/{uid}', [StatusController::class, 'update']);
  // Route pour les pistes d'audit
  Route::get('/logs', [LogsController::class, 'index']);
  Route::get('/getLogs', [LogsController::class, 'getLogs']);
});
