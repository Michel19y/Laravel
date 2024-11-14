@extends('layouts.on')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Perfil do Usuário</h2>

        <div class="row">
            <center>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-white bg-primary text-center">
                            <h5 class="mb-0">{{ $user->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ $userInfos->profileImg }}" class="img-fluid rounded-circle" alt="{{ $userInfos->name }}" style="width: 150px; height: 150px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" disabled value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" disabled value="{{ $userInfos->status }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gênero</label>
                                <input type="text" class="form-control" disabled value="{{ $userInfos->genero }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data de Nascimento</label>
                                <input type="text" class="form-control" disabled value="{{ $userInfos->dataNasc }}">
                            </div>
                        </div>
                    </div>
                </div>
            </center>    
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('userinfo.index') }}" class="btn btn-secondary">Voltar</a>
            <a href="{{ route('userinfo.edit', ['id' => Auth::user()->id]) }}" class="btn btn-secondary">Editar</a>
        </div>
    </div>
@endsection
