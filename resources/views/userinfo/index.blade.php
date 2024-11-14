@extends('layouts.on')

@section('content')
   <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary">Perfil Inicial</h2>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-lg border-light rounded">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">{{ $userInfos->name ?? 'Usuário não encontrado' }}</h5>
                    </div>
                    <div class="card-body">
                       
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="text" class="form-control" disabled value="{{ $userInfos->email ?? 'N/A' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha:</label>
                            <input type="text" class="form-control" disabled value="{{ $userInfos->password ?? 'N/A' }}">
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            @if(isset($userInfos))
                <a href="{{ route('home') }}" class="btn btn-outline-primary me-2" style="transition: all 0.3s ease;">Voltar</a>
                <a href="{{ route('userinfo.show', ['id' => Auth::user()->id]) }}" class="btn btn-primary me-2" style="transition: all 0.3s ease;">Ver mais</a>       
                <a href="{{ route('userinfo.create', $userInfos->id) }}" class="btn btn-warning" style="transition: all 0.3s ease;">Criar</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-secondary">Voltar</a>
                <p class="mt-3 text-muted">Nenhuma informação de usuário disponível.</p>
            @endif
        </div>
    </div>

    <style>
        /* Animações suaves para os botões */
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        /* Tornando o texto do título mais vibrante */
        .text-primary {
            font-weight: bold;
            color: #e9f3ffeb !important;
        }

        .card-header h5 {
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
@endsection