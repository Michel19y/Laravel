<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\PedidosController;
use Illuminate\Support\Facades\Auth;

// Rota para a página inicial
Route::get('/', [HomeController::class, 'produtoHome'])->middleware('auth')->name('welcome');
Route::get('/home', [HomeController::class, 'produtoHome'])->name('home');

// Rotas do usuário
Route::get('/userinfo/create', [UserInfoController::class, 'create'])->name('userinfo.create');
Route::get('/userinfo', [UserInfoController::class, 'index'])->name('userinfo.index');
Route::post('/userinfo/store', [UserInfoController::class, 'store'])->name('userinfo.store');
Route::get('/userinfo/show/{id}', [UserInfoController::class, 'show'])->name('userinfo.show');
Route::get('/userinfo/edit/{id}', [UserInfoController::class, 'edit'])->name('userinfo.edit');
Route::put('/userinfo/{id}', [UserInfoController::class, 'update'])->name('userinfo.update');

// Rotas de TipoProduto
Route::get('/tipoproduto', [TipoProdutoController::class, 'index'])->name('tipoproduto.index');
Route::get('/tipoproduto/create', [TipoProdutoController::class, 'create'])->name('tipoproduto.create');
Route::post('/tipoproduto', [TipoProdutoController::class, 'store'])->name('tipoproduto.store');
Route::get('/tipoproduto/{id}', [TipoProdutoController::class, 'show'])->name('tipoproduto.show');
Route::get('/tipoproduto/edit/{id}', [TipoProdutoController::class, 'edit'])->name('tipoproduto.edit');
Route::put('/tipoproduto/{id}', [TipoProdutoController::class, 'update'])->name('tipoproduto.update');

// Rotas de Produto
Route::get('/produto', [ProdutoController::class, 'index'])->name('produto.index');
Route::get('/produto/create', [ProdutoController::class, 'create'])->name('produto.create');
Route::post('/produto', [ProdutoController::class, 'store'])->name('produto.store');
Route::get('/produto/{id}', [ProdutoController::class, 'show'])->name('produto.show');
Route::get('/produto/edit/{id}', [ProdutoController::class, 'edit'])->name('produto.edit');
Route::put('/produto/{id}', [ProdutoController::class, 'update'])->name('produto.update');
Route::delete('/produto/{id}', [ProdutoController::class, 'destroy'])->name('produto.destroy');

// Rotas de Endereço
Route::get("/endereco", [EnderecoController::class, 'index'])->name('endereco.index');
Route::get("/endereco/create", [EnderecoController::class, 'create'])->name('endereco.create');
Route::post("/endereco", [EnderecoController::class, 'store'])->name('endereco.store');
Route::get("/endereco/{id}", [EnderecoController::class, 'show'])->name('endereco.show');
Route::get("/endereco/{id}/edit", [EnderecoController::class, 'edit'])->name('endereco.edit');
Route::put("/endereco/{id}", [EnderecoController::class, 'update'])->name('endereco.update');
Route::delete("/endereco/{id}", [EnderecoController::class, 'destroy'])->name("endereco.destroy");

// Rotas Do Pedidos
    Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos', [PedidosController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos', [PedidosController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}/edit', [PedidosController::class, 'edit'])->name('pedidos.edit');
    Route::put('/pedidos/{id}', [PedidosController::class, 'update'])->name('pedidos.update');
    Route::delete('/pedidos/{id}', [PedidosController::class, 'destroy'])->name('pedidos.destroy');
});

// Autenticação
Route::post('/user/logout', 'App\Http\Controllers\Auth\LoginController@userLogout')->name('user.logout');
Auth::routes();

Route::prefix('admin')->group(function () {
    // Dashboard route
    Route::get('/', 'App\Http\Controllers\AdminController@index')->name('admin.dashboard');

    // Login routes
    Route::get('/login', 'App\Http\Controllers\Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'App\Http\Controllers\Auth\AdminLoginController@login')->name('admin.login.submit');

    // Logout route
    Route::post('/logout', 'App\Http\Controllers\Auth\AdminLoginController@logout')->name('admin.logout');

    // Password reset routes
    Route::get('/password/reset', 'App\Http\Controllers\Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/email', 'App\Http\Controllers\Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset/{token}', 'App\Http\Controllers\Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('/password/reset', 'App\Http\Controllers\Auth\AdminResetPasswordController@reset')->name('admin.password.update');
});
