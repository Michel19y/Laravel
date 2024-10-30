<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\PedidosController;
use App\Models\Produto;

use function Ramsey\Uuid\v1;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota para "php artisan serve"
// Route::get('/', function () {
// $produtos = Produto::all();
//     return view('home')->with("produtos", $produtos);
// })->name('home');

// Rota para a página inicial
Route::get('/', [HomeController::class, 'produtoHome'])->name('welcome');
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

// Rotas de Pedidos
Route::get('/pedidos', [PedidosController::class, 'index'])->name('pedidos.index');
Route::post('/pedidos', [PedidosController::class, 'store'])->name('pedidos.store');  // Nome diferente para a rota POST




// Autenticação
Auth::routes();

// Rota para a página inicial novamente (redundante, pode ser removida)
Route::get('/inicio', function () {
    return view('inicio.welcome');
});
