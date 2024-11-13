@extends('layouts.on')

<!-- Link para CSS e ícone -->
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="icon" type="image" href="/img/p.png">
<style>
   
</style>

@section('content')
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
            <form id="pedidoForm" method="POST" action="{{ route('pedidos.store') }}">
                @csrf
                <div class="row">
                    @if (isset($produtos) && count($produtos) > 0)
                        @foreach ($produtos as $item)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $item->nome }}</h5>
                                        <p class="cor">Preço: <strong>R$ {{ number_format($item->preco, 2, ',', '.') }}</strong></p>
                                        <p class="cor">{{ $item->descricao }}</p>
                                        <img src="{{ $item->urlImage }}" alt="Foto do Produto" class="img-product">

                                        <div class="form-group">
                                            <label for="quantidade-{{ $item->id }}">Quantidade:</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $item->id }}, -1)">-</button>
                                                <input type="number" id="quantidade-{{ $item->id }}" name="produtos[{{ $item->id }}][quantidade]" min="0" value="0" class="form-control text-center" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $item->id }}, 1)">+</button>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="observacao-{{ $item->id }}">Observação:</label>
                                            <input type="text" id="observacao-{{ $item->id }}" name="produtos[{{ $item->id }}][observacao]" class="form-control" placeholder="Escreva suas observações aqui...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="cor">Nenhum produto disponível.</p>
                    @endif
                </div>

                <div class="form-group mt-4">
                    <label for="enderecos">Escolha o endereço:</label>
                    <select id="enderecos" name="Enderecos_id" class="form-control" required>
                        <option value="">Selecione um endereço</option>
                        @if (isset($enderecos) && count($enderecos) > 0)
                            @foreach ($enderecos as $endereco)
                                <option value="{{ $endereco->id }}">{{ $endereco->logradouro }}, {{ $endereco->numero }} - {{ $endereco->bairro }}</option>
                            @endforeach
                        @else
                            <option value="">Nenhum endereço disponível.</option>
                        @endif
                    </select>
                </div>

                <button type="button" onclick="confirmarPedido()" class="btn btn-success mt-3">Fazer Pedido</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmarPedidoModal" tabindex="-1" aria-labelledby="confirmarPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarPedidoModalLabel">Confirmação de Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Resumo do Pedido:</strong></p>
                <ul id="resumoPedido"></ul>
                <p><strong>Total:</strong> R$ <span id="totalPedido"></span></p>
                <p><strong>Endereço:</strong> <span id="enderecoSelecionado"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="enviarPedido()" class="btn btn-success">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
function changeQuantity(id, delta) {
    let input = document.getElementById('quantidade-' + id);
    let quantity = parseInt(input.value) || 0;
    quantity += delta;
    input.value = quantity < 0 ? 0 : quantity;
}

function confirmarPedido() {
    let total = 0;
    let resumoPedido = '';
    document.querySelectorAll('[id^=quantidade-]').forEach(function(input) {
        let id = input.id.split('-')[1];
        let quantidade = parseInt(input.value) || 0;
        if (quantidade > 0) {
            let nome = document.querySelector(`.card-title`).innerText;
            let preco = parseFloat(document.querySelector(`.cor strong`).innerText.replace('R$ ', '').replace(',', '.'));
            let observacao = document.getElementById(`observacao-${id}`).value || 'Sem observação';
            let subtotal = quantidade * preco;
            total += subtotal;
            resumoPedido += `<li>${nome} - Quantidade: ${quantidade}, Observação: ${observacao}, Subtotal: R$ ${subtotal.toFixed(2)}</li>`;
        }
    });

    document.getElementById('resumoPedido').innerHTML = resumoPedido;
    document.getElementById('totalPedido').innerText = total.toFixed(2);

    let endereco = document.getElementById('enderecos');
    document.getElementById('enderecoSelecionado').innerText = endereco.options[endereco.selectedIndex].text;

    $('#confirmarPedidoModal').modal('show');
}

function enviarPedido() {
    document.getElementById('pedidoForm').submit();
}

setTimeout(function() {
    const messageDiv = document.querySelector('.mens');
    if (messageDiv) {
        messageDiv.style.display = 'none';
    }
}, 2000);
</script>
