@extends('layouts.on')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Perfil do Usuário</h2>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-white bg-primary text-center">
                    <h5 class="mb-0">{{ $userInfos->name ?? 'Usuário não encontrado' }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('userinfo.update', ['id' => $userInfos->Users_id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-3">
                            <img src="{{ $userInfos->profileImg ?? 'caminho/para/imagem/default.png' }}" class="img-fluid rounded-circle" alt="{{ $userInfos->name ?? 'Usuário' }}" style="width: 150px; height: 150px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $userInfos->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $userInfos->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" name="status" value="{{ old('status', $userInfos->status) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gênero</label>
                            <input type="text" class="form-control" name="genero" value="{{ old('genero', $userInfos->genero) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="dataNasc" value="{{ old('dataNasc', $userInfos->dataNasc) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagem de Perfil</label>
                            <input type="file" class="form-control" name="profileImg">
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <a href="{{ route('userinfo.index') }}" class="btn btn-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
