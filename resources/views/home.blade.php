@extends('layouts.on')

<!-- Link para CSS e ícone -->
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="icon" type="image" href="/img/p.png">

@section('content')

<!-- Mensagem de login -->
<div class="mens">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-body">
                    @if (Auth::check())
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h3 class="text-success">{{ __('ingles.You are logged in!') }}</h3>
                        <p>Pronto para explorar nosso cardápio do submundo?</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Section -->
<div class="hero-section text-center">
    <div class="hero-text">
        <h1>Bem-vindo à Pizzaria do Caronte</h1>
        <p>Onde cada mordida é uma jornada ao submundo.</p>
    </div>
</div>

<!-- Menu Section -->
<div class="menu-section" id="menu">
    <div class="container">
        <h2 class="text-center">Nosso Menu</h2>
        <div class="container mt-4">
            <h2 class="text-center">Lista de Produtos</h2>
            <form action="{{ route('pedidos.store') }}" method="POST">
                @csrf
                <div class="row">
                    @if(isset($produtos) && count($produtos) > 0)
                        @foreach ($produtos as $item)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $item->nome }}</h5>
                                        <p>Preço: <strong>R$ {{ number_format($item->preco, 2, ',', '.') }}</strong></p>
                                        <p>{{ $item->descricao }}</p>
                                        <img src="{{ $item->urlImage }}" alt="Foto do Produto" class="img-product">
                
                                        <!-- Campo para selecionar a quantidade -->
                                        <div class="form-group">
                                            <label for="quantidade-{{ $item->id }}">Quantidade:</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $item->id }}, -1)">-</button>
                                                <input type="number" id="quantidade-{{ $item->id }}" name="produtos[{{ $item->id }}][quantidade]" min="0" value="0" class="form-control text-center" required readonly>
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $item->id }}, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Nenhum produto disponível.</p>
                    @endif
                </div>

                <!-- Campo para selecionar o endereço -->
                <div class="form-group mt-4">
                    <label for="enderecos">Escolha o endereço:</label>
                    <select id="enderecos" name="Enderecos_id" class="form-control" required>
                        <option value="">Selecione um endereço</option>
                        @if(isset($enderecos) && count($enderecos) > 0)
                            @foreach ($enderecos as $enderecos)
                                <option value="{{ $enderecos->id }}">{{ $enderecos->logradouro }}, {{ $enderecos->numero }} - {{ $enderecos->bairro }}</option>
                            @endforeach
                        @else
                            <option value="">Nenhum endereço disponível</option>
                        @endif
                    </select>
                </div>

                <!-- Botão para gerar o pedido -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-vibrant">Gerar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para modificar quantidade -->
<script>
    function changeQuantity(productId, change) {
        var quantityInput = document.getElementById('quantidade-' + productId);
        var currentQuantity = parseInt(quantityInput.value);

        // Atualiza a quantidade com base no botão pressionado
        var newQuantity = currentQuantity + change;

        // Garante que a quantidade não seja menor que 0
        if (newQuantity < 0) {
            newQuantity = 0;
        }

        // Atualiza o valor do input
        quantityInput.value = newQuantity;
    }
</script>

<!-- Mensagem de status com fade-out -->
<script>
    window.onload = function() {
        const alertBox = document.querySelector('.mens');

        if (alertBox) {
            // Esconde a mensagem após 2 segundos
            setTimeout(() => {
                alertBox.style.transition = "opacity 0.5s ease"; // Transição suave
                alertBox.style.opacity = 0; // Faz a mensagem ficar invisível

                // Após a transição, remove o elemento do DOM
                setTimeout(() => {
                    alertBox.remove();
                }, 500); // Aguarda a transição antes de remover
            }, 2000);
        }
    };
</script>

<!-- Rodapé -->
<footer class="text-center mt-4">
    <p>&copy; 2024 Pizzaria do Caronte. Todos os direitos reservados. Ouse cruzar o rio.</p>
</footer>

@endsection
