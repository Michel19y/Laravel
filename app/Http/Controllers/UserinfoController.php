<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserInfoController extends Controller
{
    public function __construct()
    {
        // Se precisar de middleware, adicione aqui
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado.');
        }
    
        $user = Auth::user();
    
        // Busca informações do usuário
        $userInfos = DB::table('users')
            ->select('users.*')  // Seleciona as colunas da tabela 'users'
            ->where('users.id', '=', $user->id)  // Aplica o filtro para o ID especificado
            ->first();  // Obtém o primeiro resultado
    
        // Se as informações do usuário não existirem, redireciona para 'userinfo.create'
        if (!$userInfos) {
            return redirect()->route('userinfo.create')->with('error', 'Informações do usuário não encontradas.');
        }
    
        // Retorna a view com as informações do usuário
        return view('userinfo.index')->with("userInfos", $userInfos);
    }

    /**
     * Show the form for creating a new resource.
  
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Busca informações dos usuários e de user_infos
        // Busca informações de todos os usuários
        $user = Auth::user(); // Busca todos os usuários
        // Busca informações de user_infos com os usuários relacionados
        $userInfo = UserInfo::find($user->id);

        return view("userInfo.create")->with("userInfo", $userInfo)->with("user", $user); // Passa as informações para a view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado.');
        }
    
        // Cria um novo registro em user_infos
        $userInfo = new UserInfo();
        $userInfo->Users_id = Auth::user()->id; // Associa o registro ao usuário autenticado
        $userInfo->status = $request->status;
        $userInfo->dataNasc = $request->dataNasc;
        $userInfo->genero = $request->genero;
    
        // Verifica se foi enviado um arquivo de imagem
        if ($request->hasFile('profileImg')) {
            $imagem = $request->file('profileImg');
    
            // Cria um nome único para a imagem com base no ID do usuário e timestamp
            $nomeImagem = Auth::user()->id . '-' . time() . '.' . $imagem->getClientOriginalExtension();
    
            // Define o caminho onde a imagem será guardada
            $caminhoImagem = public_path('img/perfil');
    
            // Cria a pasta se ela não existir
            if (!file_exists($caminhoImagem)) {
                mkdir($caminhoImagem, 0755, true);
            }
    
            // Move a imagem para o diretório
            $imagem->move($caminhoImagem, $nomeImagem);
    
            // Atualiza o campo profileImg no objeto userInfo com o caminho relativo da imagem
            $userInfo->profileImg = "/img/perfil/" . $nomeImagem;
        } else {
            // Se não tiver imagem, define uma imagem padrão
            $userInfo->profileImg = "/img/perfil/default.png"; // Caminho da imagem padrão
        }
    
        // Salva o registro com as informações fornecidas
        $userInfo->save();
    
        // Retorna para a página de informações com uma mensagem de sucesso
        return redirect()->route('userinfo.index')->with('success', 'Informações salvas com sucesso!');
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Busca as informações do user_info e carrega o usuário relacionado
        $userInfos = UserInfo::with('user')->findOrFail($id);
        $user = Auth::user();

        // Retorna a view com as informações do user_info
        return view("userinfo.show")->with("userInfos", $userInfos)->with("user", $user);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado.');
        }

        // Busca informações do usuário
        $userInfos = DB::table('user_infos')
            ->join('users', 'user_infos.Users_id', '=', 'users.id') // Verifique se 'Users_id' está correto
            ->select('user_infos.*', 'users.*') // Seleciona todas as colunas de ambas as tabelas
            ->where('user_infos.Users_id', '=', $id) // Certifique-se de que 'Users_id' é a coluna correta
            ->first(); // Obtém o primeiro resultado

        if (!$userInfos) {
            return redirect()->back()->with('error', 'Informações do usuário não encontradas.');
        }

        // Retorna a view com as informações do usuário
        return view('userinfo.edit')->with("userInfos", $userInfos);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validação dos campos enviados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'status' => 'nullable|string|max:255',
            'genero' => 'nullable|string|max:255',
            'dataNasc' => 'nullable|date',
            'profileImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validação da imagem
        ]);

        try {
            // Busca o usuário no banco de dados
            $userInfo = UserInfo::findOrFail($id);
            $user = User::findOrFail($userInfo->Users_id);


            // Atualiza as informações do usuário
            $user->name = $request->name;
            $user->email = $request->email;
            $userInfo->status = $request->status;
            $userInfo->genero = $request->genero;
            $userInfo->dataNasc = $request->dataNasc;

            // Verifica se há uma nova imagem de perfil
            if ($request->hasFile('profileImg')) {
                $imagem = $request->file('profileImg');

                // Cria um nome único para a imagem com base no nome do usuário e no timestamp
                $nomeImagem = $user->name . '-' . time() . '.' . $imagem->getClientOriginalExtension();

                // Define o caminho onde a imagem será guardada
                $caminhoImagem = public_path('img/perfil');

                // Cria a pasta se ela não existir
                if (!file_exists($caminhoImagem)) {
                    mkdir($caminhoImagem, 0755, true);
                }

                // Move a nova imagem para o diretório
                $imagem->move($caminhoImagem, $nomeImagem);

                // Remove a imagem antiga se não for a padrão
                if ($userInfo->profileImg && file_exists(public_path($userInfo->profileImg)) && $userInfo->profileImg != "/img/perfil/default.png") {
                    unlink(public_path($userInfo->profileImg));
                }

                // Atualiza o caminho da imagem no banco de dados
                $userInfo->profileImg = "/img/perfil/" . $nomeImagem;
            }

            // Salva as alterações
            $userInfo->update();
            $user->update();

            // Redireciona com sucesso
            return redirect()->route('userinfo.index', ['id' => $id])->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            // Redireciona com erro caso algo falhe
            dd($e);
            return redirect()->back()->with('error', 'Erro ao atualizar o perfil: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Exclui as informações de user_infos
        $userInfo = UserInfo::where('user_id', $id)->first();

        if (!$userInfo) {
            return redirect()->back()->with('error', 'Informações do usuário não encontradas.');
        }

        $userInfo->delete();

        return redirect()->route('userInfo.index', ['id' => Auth::id()])->with('success', 'Informações excluídas com sucesso.');
    }
}
