<?php
namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Faz a consulta para obter os produtos
        $produtos = DB::select('
            SELECT Produtos.*, Tipo_Produtos.descricao 
            FROM Produtos 
            JOIN Tipo_Produtos ON Produtos.Tipo_Produtos_id = Tipo_Produtos.id
        ');

        // Retorna a view "home" com a lista de produtos
        return view("home")->with("produtos", $produtos);
    }

    public function produtoHome()
{
    // Verifica se o usuário está autenticado no guard de admin ou de usuário comum
    if (!Auth::guard('admin')->check() && !Auth::check()) {
        return redirect()->route('home')->with('error', 'Você precisa estar logado para ver seus pedidos.');
    }

    // Verifica se o usuário está autenticado como admin no guard 'admin'
    $isAdmin = Auth::guard('admin')->check();

    // Obtém o ID do usuário autenticado:
    // Se for admin, usa o guard 'admin'; caso contrário, usa o usuário comum no guard padrão 'web'
    $userId = $isAdmin ? Auth::guard('admin')->user()->id : Auth::user()->id;

    // Consulta para obter os produtos e suas descrições de tipo
    $produtos = DB::select('
        SELECT Produtos.*, Tipo_Produtos.descricao 
        FROM Produtos 
        JOIN Tipo_Produtos ON Produtos.Tipo_Produtos_id = Tipo_Produtos.id
    ');

    // Consulta para obter os endereços do usuário autenticado
    $enderecos = Endereco::where('Users_id', $userId)->get();

   

    // Retorna a view "home" com a lista de produtos e endereços
    return view("home")->with("produtos", $produtos)->with("enderecos", $enderecos);
}

}
