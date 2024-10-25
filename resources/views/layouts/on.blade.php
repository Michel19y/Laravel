<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image" href="/img/p.png">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <style>
        .btn-botoes {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 10px;
            background-color: #000000;
            text-align: center;
            color: white;
            flex: 1;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>  
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @auth
                        <div class="d-flex align-items-center">
                            <div class="row g-3">
                                <div class="col-md-auto">
                                    <a href="{{ route('produto.index') }}">
                                        <div class="btn btn-botoes">Produto</div>
                                    </a>
                                </div>
                                <div class="col-md-auto">
                                    <a href="{{ route('tipoproduto.index') }}">
                                        <div class="btn btn-botoes">Tipo de Produto</div>
                                    </a>
                                </div>
                                <div class="col-md-auto">
                                    <a href="{{ route('endereco.index') }}">
                                        <div class="btn btn-botoes">Endereços</div>
                                    </a>
                                </div>
                                <div class="col-md-auto">
                                    <a href="{{ route('pedidos.index') }}">
                                        <div class="btn btn-botoes">Pedidos</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('ingles.Logout') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('userinfo.index', ['id' => Auth::user()->id]) }}">Minha conta</a>
                                    <a class="dropdown-item" href="{{ route('userinfo.create') }}">Criar</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
