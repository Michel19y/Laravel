<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index()
    {
        
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para ver seus pedidos.');
        }
    
        // Obtém o usuário autenticado
        $user = Auth::user();
    
        // Consulta SQL para obter os pedidos do usuário com seus produtos e o total calculado
        $pedidos = DB::select("
            SELECT 
                pedidos.id,
                pedidos.status,
                pedidos.created_at,
                GROUP_CONCAT(pedido_produtos.quantidade SEPARATOR ', ') AS quantidade_total,
                GROUP_CONCAT(produtos.nome SEPARATOR ', ') AS produtos_nome,
                GROUP_CONCAT(pedido_produtos.observacao SEPARATOR ', ') AS observacoes, -- Adiciona a coluna observacao
                SUM(pedido_produtos.quantidade * produtos.preco) AS total_preco,
                enderecos.logradouro,
                enderecos.bairro,
                enderecos.numero,
                users.name AS cliente_nome
            FROM pedidos
            JOIN pedido_produtos ON pedidos.id = pedido_produtos.Pedidos_id
            JOIN produtos ON pedido_produtos.Produtos_id = produtos.id
            JOIN enderecos ON pedidos.Enderecos_id = enderecos.id
            JOIN users ON pedidos.Users_id = users.id
            WHERE pedidos.Users_id = ?
            GROUP BY 
                pedidos.id, 
                pedidos.status, 
                pedidos.created_at, 
                enderecos.logradouro, 
                enderecos.bairro, 
                enderecos.numero, 
                users.name
            ORDER BY pedidos.id
        ", [$user->id]);
    
        // Adiciona o total calculado para cada pedido
        $pedidos = collect($pedidos)->map(function ($pedido) {
            $pedido->total = $pedido->total_preco; // Total calculado na consulta
            return $pedido;
        });
    
        // Retorna a view com os pedidos do usuário
        return view('pedidos.index')->with(['pedidos' => $pedidos]);
    }
    


    public function store(Request $request)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para realizar um pedido.');
        }

        // Obtém o usuário autenticado
        $user = Auth::user();



        // Busca informações detalhadas do usuário
        $userInfos = DB::table('users')
            ->select('users.*')  // Seleciona as colunas da tabela 'users'
            ->where('users.id', '=', $user->id)  // Filtro pelo ID do usuário autenticado
            ->first();  // Obtém o primeiro resultado

        // Verificação adicional para garantir que o usuário existe no banco de dados
        if (!isset($userInfos)) {
            return redirect()->route('home')->with('error', 'Usuário não encontrado.');
        }

        // dd($request);

        // // Validação dos dados recebidos no request
        // $request->validate([
        //     'produtos.*.quantidade' => 'required|integer|min:1',
        //     'Enderecos_id' => 'required|integer',
        // ]);

        // Inicia a transação
        DB::beginTransaction();
        try {
            // Criação do Pedido
            $pedido = new Pedido();
            $pedido->Users_id = $user->id; // Atribui o ID do usuário autenticado ao pedido
            $pedido->Enderecos_id = $request->input('Enderecos_id');
            $pedido->status = 'p';


            $pedido->save();

            // Inserir cada produto relacionado ao pedido
            foreach ($request->input('produtos') as $produtoId => $produtoData) {
                if (isset($produtoData['quantidade']) && $produtoData['quantidade'] > 0) {
                    $produtoPedido = new PedidoProduto();
                    $produtoPedido->Pedidos_id = $pedido->id;
                    $produtoPedido->Produtos_id = $produtoId;
                    $produtoPedido->quantidade = $produtoData['quantidade'];
                    $produtoPedido->observacao = $produtoData['observacao'] ?? null;
                    $produtoPedido->save();
                }
            }

            // Confirma a transação
            DB::commit();

            // Redireciona para pedidos.index com uma mensagem de sucesso
            return redirect()->route('pedidos.index')->with('success', 'Pedido gerado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, desfaz a transação e retorna com mensagem de erro
            DB::rollBack();
            dd($e);
            return redirect()->route('pedidos.index')->with('error', 'Falha ao gerar o pedido: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $pedido = Pedido::with('produtos')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit($id)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }
    
        $pedido = Pedido::with(['produtos', 'endereco'])->findOrFail($id);
        return view('pedidos.edit', compact('pedido'));
    }
    

    public function update(Request $request, $id)
    {
        // Verifica se o utilizador está autenticado
        if (!Auth::check()) {
            return redirect()->route('pedidos.index')->with('error', 'Acesso negado.');
        }
    
        // Validação dos dados de entrada
        $request->validate([
            'status' => 'required|string|in:p,a,c',
            'produtos.*.quantidade' => 'required|integer|min:1',
        ]);
    
        DB::beginTransaction();
        try {
            // Atualiza o pedido com o novo status
            $pedido = Pedido::findOrFail($id);
            $pedido->status = $request->input('status');
            $pedido->save();
    
            // Atualiza os produtos do pedido na tabela intermediária
            foreach ($request->input('produtos') as $produtoId => $produtoData) {
                $pedidoProduto = $pedido->produtos()->where('Produtos_id', $produtoId)->first();
    
                if ($pedidoProduto) {
                    $pedidoProduto->pivot->quantidade = $produtoData['quantidade'];
                    $pedidoProduto->pivot->observacao = $produtoData['observacao'] ?? null;
                    $pedidoProduto->pivot->save(); // Salva diretamente no pivot
                }
            }
    
            DB::commit(); 
    
            return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pedidos.index')->with('error', 'Erro ao atualizar o pedido: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
    DB::beginTransaction(); // Inicia a transação
    try {
    $produto = Pedido::find($id);
    if (isset($pedido)) {
    $pedido->delete();
   
    }
    DB::commit(); // Confirma a transação
    return redirect()->route("pedidos.index");
    } catch (\Throwable $th) {
    DB::rollBack(); // Desfaz a transação em caso de erro
    dd($th);
    return redirect()->route("pedidos.index");
    }
    }
}