@extends('layouts.on')

<!-- Link para CSS e ícone -->
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="icon" type="image" href="/img/p.png">
<style>
    .card-title {
    margin-bottom: var(--bs-card-title-spacer-y);
    color: black
}
.cor{
    color: black;
}
.img-product {
    width: 100%; /* A largura da imagem ocupará 100% da largura do cartão */
    height: auto; /* Mantém a proporção original da imagem */
    max-width: 200px; /* Define um valor máximo para não esticar demais em telas maiores */
    margin: 0 auto; /* Centraliza a imagem dentro do cartão */
}

/* Adicione um ajuste para tamanhos de tela menores */
@media (max-width: 868px) {
    .card {
        margin-bottom: 20px; /* Espaço entre os cartões em telas menores */
    }
}



</style>
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
                        @if (isset($produtos) && count($produtos) > 0)
                            @foreach ($produtos as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $item->nome }}</h5>
                                            <p class="cor
                                            ">Preço: <strong>R$ {{ number_format($item->preco, 2, ',', '.') }}</strong></p>
                                            <p class="cor">{{ $item->descricao }}</p>
                                            <img src="{{ $item->urlImage }}" alt="Foto do Produto" class="img-product">

                                            <!-- Campo para selecionar a quantidade -->
                                            <div class="form-group">
                                                <label for="quantidade-{{ $item->id }}">Quantidade:</label>
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        onclick="changeQuantity({{ $item->id }}, -1)">-</button>
                                                    <input type="number" id="quantidade-{{ $item->id }}"
                                                        name="produtos[{{ $item->id }}][quantidade]" min="0"
                                                        value="0" class="form-control text-center" required>
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        onclick="changeQuantity({{ $item->id }}, 1)">+</button>
                                                </div>
                                            </div>

                                            <!-- Campo de observação -->
                                            <div class="form-group mt-2">
                                                <label for="observacao-{{ $item->id }}">Observação:</label>
                                                <input type="text" id="observacao-{{ $item->id }}" 
                                                    name="produtos[{{ $item->id }}][observacao]" 
                                                    class="form-control text-center" 
                                                    placeholder="Escreva suas observações aqui...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                             <p class="cor">Nenhum produto disponível.</p>
                        @endif
                    </div>

                    <!-- Campo para selecionar o endereço -->
                    <div class="form-group mt-4">
                        <label for="enderecos">Escolha o endereço:</label>
                        <select id="enderecos" name="Enderecos_id" class="form-control" required>
                            <option value="">Selecione um endereço</option>
                            @if (isset($enderecos) && count($enderecos) > 0)
                                @foreach ($enderecos as $endereco)
                                    <option value="{{ $endereco->id }}">
                                        {{ $endereco->logradouro }}, {{ $endereco->numero }} - {{ $endereco->bairro }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Nenhum endereço disponível.</option>
                            @endif
                        </select>
                    </div>

                    <!-- Botão de enviar -->
                    <button type="submit" class="btn btn-success mt-3">Fazer Pedido</button>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    function changeQuantity(id, delta) {
        let input = document.getElementById('quantidade-' + id);
        let quantity = parseInt(input.value) || 0; // Obtém o valor atual ou 0
        quantity += delta; // Adiciona ou subtrai
        input.value = quantity < 0 ? 0 : quantity; // Garante que a quantidade não fique negativa
    }
</script>
