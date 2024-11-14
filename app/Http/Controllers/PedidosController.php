<?php

namespace App\Http\Controllers;
    use Carbon\Carbon;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
  
    
class PedidosController extends Controller
{
   

 
    public function index(Request $request)
    {
        // Verifica se o usuário está autenticado no guard de admin ou de usuário
        if (!Auth::guard('admin')->check() && !Auth::check()) {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para ver seus pedidos.');
        }
    
        // Verifica se o usuário está autenticado como admin
        $isAdmin = Auth::guard('admin')->check();
    
        // Obtém o ID do usuário autenticado
        $userId = $isAdmin ? Auth::guard('admin')->user()->id : Auth::user()->id;
    
        // Captura as datas de início e fim dos filtros e converte para o formato yyyy-mm-dd
        $dataInicio = $request->input('data_inicio') 
            ? Carbon::createFromFormat('d/m/Y', $request->input('data_inicio'))->format('Y-m-d') 
            : null;
        $dataFim = $request->input('data_fim') 
            ? Carbon::createFromFormat('d/m/Y', $request->input('data_fim'))->format('Y-m-d') 
            : null;
    
        // Define a consulta SQL com base no tipo de usuário
        $query = "SELECT 
                    pedidos.id,
                    pedidos.status,
                    pedidos.created_at,
                    GROUP_CONCAT(pedido_produtos.quantidade SEPARATOR ', ') AS quantidade_total,
                    GROUP_CONCAT(produtos.nome SEPARATOR ', ') AS produtos_nome,
                    GROUP_CONCAT(produtos.preco SEPARATOR ', ') AS produtos_preco,
                    GROUP_CONCAT(pedido_produtos.observacao SEPARATOR ', ') AS observacoes,
                    SUM(pedido_produtos.quantidade * produtos.preco) AS total_preco,
                    enderecos.logradouro,
                    enderecos.bairro,
                    enderecos.numero,
                    users.name AS cliente_nome
                FROM pedidos
                JOIN pedido_produtos ON pedidos.id = pedido_produtos.Pedidos_id
                JOIN produtos ON pedido_produtos.Produtos_id = produtos.id
                LEFT JOIN enderecos ON pedidos.Enderecos_id = enderecos.id
                JOIN users ON pedidos.Users_id = users.id ";
    
        // Define os parâmetros e as condições para o tipo de usuário e filtro de datas
        $params = [];
        $conditions = [];
    
        if (!$isAdmin) {
            $conditions[] = "pedidos.Users_id = ?";
            $params[] = $userId;
        }
    
        if ($dataInicio && $dataFim) {
            $conditions[] = "pedidos.created_at BETWEEN ? AND ?";
            $params[] = $dataInicio . ' 00:00:00';
            $params[] = $dataFim . ' 23:59:59';
        } elseif ($dataInicio) {
            $conditions[] = "pedidos.created_at >= ?";
            $params[] = $dataInicio . ' 00:00:00';
        } elseif ($dataFim) {
            $conditions[] = "pedidos.created_at <= ?";
            $params[] = $dataFim . ' 23:59:59';
        }
    
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $query .= " GROUP BY 
                        pedidos.id, 
                        pedidos.status, 
                        pedidos.created_at, 
                        enderecos.logradouro, 
                        enderecos.bairro, 
                        enderecos.numero, 
                        users.name
                    ORDER BY pedidos.id";
    
        // Executa a consulta com os parâmetros definidos
        $pedidos = DB::select($query, $params);
    
        // Processa os pedidos para formatar os dados dos produtos como arrays
        $pedidos = collect($pedidos)->map(function ($pedido) {
            // Transforma as strings concatenadas em arrays
            $pedido->produtos = explode(', ', $pedido->produtos_nome);
            $pedido->quantidades = explode(', ', $pedido->quantidade_total);
            $pedido->precos = explode(', ', $pedido->produtos_preco);
            $pedido->observacoes = explode(', ', $pedido->observacoes);
    
            // Substitui o endereço por "Buscar na Pizzaria" se os valores forem null
            if (is_null($pedido->logradouro) && is_null($pedido->bairro) && is_null($pedido->numero)) {
                $pedido->endereco = "Buscar na Pizzaria";
            } else {
                $pedido->endereco = "{$pedido->logradouro}, {$pedido->numero} - {$pedido->bairro}";
            }
    
            // Adiciona o total calculado para cada pedido
            $pedido->total = $pedido->total_preco;
            
            return $pedido;
        });
    
        // Retorna a view com os pedidos e filtros de data
        return view('pedidos.index', [
            'pedidos' => $pedidos, 
            'dataInicio' => $request->input('data_inicio'), // Envia de volta o formato dd/mm/yyyy
            'dataFim' => $request->input('data_fim') // Envia de volta o formato dd/mm/yyyy
        ]);
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
        if (!Auth::guard('web')->check()) {
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
        DB::beginTransaction();
        
        try {
            $pedido = Pedido::find($id);
            
            if (!$pedido) {
                throw new \Exception('Pedido não encontrado.');
            }
            // dd($pedido);

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $pedido->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();
            
            return redirect()->route("pedidos.index")->with('success', 'Pedido excluído com sucesso.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Erro ao excluir o pedido: ' . $th->getMessage());
            
            return redirect()->route("pedidoes.index")->with('error', 'Ocorreu um erro ao tentar excluir o pedido.');
        }
    }
    
    
    
}