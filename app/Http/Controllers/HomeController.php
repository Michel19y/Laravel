<?php
namespace App\Http\Controllers;

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
        // Obtém o ID do usuário autenticado
        $userId = Auth::id();
    
        // Consulta para obter os produtos e suas descrições de tipo
        $produtos = DB::select('
            SELECT Produtos.*, Tipo_Produtos.descricao 
            FROM Produtos 
            JOIN Tipo_Produtos ON Produtos.Tipo_Produtos_id = Tipo_Produtos.id
        ');
    
        // Consulta para obter os endereços do usuário autenticado
        $enderecos = DB::table('enderecos')->where('Users_id', $userId)->get();
    
        // Retorna a view "home" com a lista de produtos e endereços
        return view("home")->with("produtos", $produtos)->with("enderecos", $enderecos);
    }
}
