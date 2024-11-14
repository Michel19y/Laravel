<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Endereco;
use Illuminate\Support\Facades\Auth;

class EnderecoController extends Controller
{
    /**
     * 
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            $id = Auth::id(); // Obtém o ID do usuário autenticado de forma simplificada
            $message = Session::get("message"); // Obtém a mensagem da sessão, se existir
            $enderecos = DB::table('Enderecos')->where('Users_id', $id)->get(); // Consulta para obter endereços do usuário
    
            return view("Endereco/index")
                ->with("enderecos", $enderecos)
                ->with("message", $message);
        } catch (\Throwable $th) {
            return view("Endereco/index")
                ->with("enderecos", [])
                ->with("message", [$th->getMessage(), "danger"]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view("Endereco/create");
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // Verifica se o usuário está autenticado no guard de admin ou de usuário comum
    if (!Auth::guard('admin')->check() && !Auth::check()) {
        return redirect()->route('home')->with('error', 'Você precisa estar logado para adicionar um endereço.');
    }

    // Verifica se o usuário está autenticado como admin
    $isAdmin = Auth::guard('admin')->check();

    // Obtém o ID do usuário autenticado (se for admin, usa o guard admin; se não, usa o usuário comum)
    $userId = $isAdmin ? Auth::guard('admin')->user()->id : Auth::id();

    // Valida os dados recebidos
    

    try {
        // Cria um novo endereço
        $endereco = new Endereco();
        $endereco->Users_id = $userId; // Substitua "Users_id" pelo nome correto do campo na sua tabela
        $endereco->bairro = $request->bairro;
        $endereco->logradouro = $request->logradouro;
        $endereco->numero = $request->numero;
        $endereco->complemento = $request->complemento;
        
        // Salva o endereço no banco de dados
        $endereco->save();

        // Redireciona para a lista de endereços com uma mensagem de sucesso
        return redirect()->route("endereco.index")->with("message", ["Endereço salvo com sucesso.", "success"]);


    } catch (\Throwable $th) {
        // Redireciona para a lista de endereços com uma mensagem de erro
        return redirect()->route("endereco.index")->with("message", [$th->getMessage(), "danger"]);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $enderecos = DB::select('SELECT * from Enderecos WHERE id = ?', [$id]);
            if (count($enderecos) == 1) {
                return view("Endereco/show")->with("endereco", $enderecos[0]);
            }
            return redirect()->route("endereco.index")->with("message", ["Endereço $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("endereco.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $endereco = Endereco::find($id);
            if (isset($endereco)) {
                return view("Endereco/edit")->with("endereco", $endereco);
            }
            return redirect()->route("endereco.index")->with("message", ["Endereço $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("endereco.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $endereco = Endereco::find($id);
            if (isset($endereco)) {
                $endereco->bairro = $request->bairro;
                $endereco->logradouro = $request->logradouro;
                $endereco->numero = $request->numero;
                $endereco->complemento = $request->complemento;
                $endereco->update();
                return redirect()->route("endereco.index")->with("message", ["Endereço atualizado com sucesso.", "success"]);
            }
            return redirect()->route("endereco.index")->with("message", ["Endereço $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("endereco.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $endereco = Endereco::find($id);
            if (isset($endereco)) {
                $endereco->delete();
                return redirect()->route("endereco.index")->with("message", ["Endereço $id removido com sucesso.", "success"]);
            }
            return redirect()->route("endereco.index")->with("message", ["Endereço $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("endereco.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }
}
