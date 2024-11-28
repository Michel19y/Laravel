<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\TipoProduto;
use Illuminate\Support\Facades\Storage;


class TipoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Realiza a consulta para buscar todos os dados da tabela Tipo_Produtos
            $tipoProdutos = DB::select('SELECT * FROM Tipo_Produtos');
    
            // Retorna a view com os dados obtidos
            return view("tipoproduto.index")
                ->with("tipoProdutos", $tipoProdutos)
                ->with("message", ["Operação realizada com sucesso!", "success"]); // Mensagem de sucesso
        } catch (\Throwable $th) {
            // Trata erros e retorna uma mensagem apropriada
            return view("tipoproduto.index")
                ->with("tipoProdutos", []) // Retorna uma lista vazia caso ocorra erro
                ->with("message", [$th->getMessage(), "danger"]); // Mensagem de erro
        }
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("tipoproduto.create");
    }

    public function inicio()
    {
    return view("/inicio.welcome");

        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Inicia uma transação
        DB::beginTransaction();
    
        try {
            // Cria uma nova instância do modelo TipoProduto
            $tipoProduto = new TipoProduto();
            $tipoProduto->descricao = $request->descricao; // Define o valor da descrição
            $tipoProduto->save(); // Salva o registro no banco de dados
    
            // Confirma a transação se tudo der certo
            DB::commit();
    
            // Redireciona para a lista com uma mensagem de sucesso
            return redirect()->route('tipoproduto.index')
                ->with("message", ["Tipo de produto criado com sucesso!", "success"]);
        } catch (\Throwable $th) {
            // Desfaz a transação em caso de erro
            DB::rollback();
    
            // Redireciona para a página inicial com uma mensagem de erro
            return redirect()->route('tipoproduto.index')
                ->with("message", ["Erro ao criar tipo de produto: " . $th->getMessage(), "danger"]);
        }
    }
      

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tipoProdutos = DB::select('SELECT * from Tipo_Produtos WHERE id = ?', [$id]);
            if (count($tipoProdutos) == 1) {
                return view("TipoProduto/show")->with("tipoProduto", $tipoProdutos[0]);
            }
            return redirect()->route("tipoproduto.index")->with("message", ["TipoProduto $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("tipoproduto.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }
        
        
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $tipoProduto = TipoProduto::find($id);
            if (isset($tipoProduto)) {
                return view("TipoProduto/edit")->with("tipoProduto", $tipoProduto);
            }
            return redirect()->route("tipoproduto.index")->with("message", ["TipoProduto $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("tipoproduto.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $tipoProduto = TipoProduto::find($id);
            if (isset($tipoProduto)) {
                $tipoProduto->descricao = $request->descricao;
                $tipoProduto->update();
                return redirect()->route("tipoproduto.index")->with("message", ["TipoProduto atualizado com sucesso.", "success"]);
            }
            return redirect()->route("tipoproduto.index")->with("message", ["TipoProduto $id não encontrado.", "warning"]);
        } catch (\Throwable $th) {
            return redirect()->route("tipoproduto.index")->with("message", [$th->getMessage(), "danger"]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
