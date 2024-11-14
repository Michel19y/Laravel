@extends('layouts.on')

<!-- CSS do Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- JavaScript do Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Tradução para Português -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<style>   
    body {
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        max-width: 1200px;
    }
/* Estilo para o botão "X" */
.close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #333;
        cursor: pointer;
    }

    .card-header {
        background-color: #28a745;
    }

    h2.text-center {
        color: #28a745;
        font-weight: bold;
    }
    
    .modal-body {
        color: black;
    }
    
    #exampleModalLabel {
        color: black !important;
    }

    /* Corpo do Cartão */
    .card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background-color: #28a745 !important;
        color: #fff;
        font-weight: bold;
        font-size: 1.1em;
        text-align: center;
    }

    .card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .info-group {
        margin-bottom: 1rem;
        color: #333;
    }

    .info-group span.fw-bold {
        color: #495057;
    }

    /* Estilo do ícone no campo de entrada */
    .input-icon {
        position: relative;
    }

    .input-icon input {
        padding-left: 2.5rem;
    }

    .input-icon i {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: #28a745;
    }

    /* Botão de Edição */
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        padding: 8px 20px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Botão Voltar para o Menu */
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border: none;
        padding: 10px 20px;
        margin-top: 20px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #565e64;
    }
    #mensagem{
        color: #3597bb;
    }

    /* Responsividade para Telas Menores */
    @media (max-width: 576px) {
        .card-header {
            font-size: 1em;
        }
        .info-group {
            font-size: 0.9em;
        }
    }
</style>

@section('content')
<div class="container mt-5">
    <p id="mensagem" class="alert alert-info position-relative">
        {{$message}}
        <button type="button" class="close-btn" onclick="removerMensagem()">X</button>
    </p>
    @if (isset($erro))

    
    <p>{{$erro}}</p>
        
    @endif
    


    <h2 class="text-center mb-4">Meus Pedidos</h2>

    <!-- Filtro de Data -->
    <form method="GET" action="{{ route('pedidos.index') }}" class="mb-4 d-flex justify-content-center">
        <div class="form-group input-icon me-2">
            <i class="fas fa-calendar-alt"></i>
            <input type="text" name="data_inicio" id="data_inicio" class="form-control" placeholder="Data Início" value="{{ request()->get('data_inicio', session('data_inicio')) }}">
        </div>
        <div class="form-group input-icon me-2">
            <i class="fas fa-calendar-alt"></i>
            <input type="text" name="data_fim" id="data_fim" class="form-control" placeholder="Data Fim" value="{{ request()->get('data_fim', session('data_fim')) }}">
        </div>
        <button type="submit" class="btn btn-primary align-self-end"><i class="fas fa-filter me-1"></i>Filtrar</button>
    </form>

    <!-- Lista de Pedidos -->
    <div class="row justify-content-center">
        @if(isset($pedidos) && count($pedidos) > 0)
            @foreach($pedidos as $pedido)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Pedido #{{ $pedido->id }}</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-calendar-alt me-2"></i>Data:</span>
                                <span>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }} | Hora: {{ \Carbon\Carbon::parse($pedido->created_at)->format('H:i:s') }}</span>
                            </div>
                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-user me-2"></i>Cliente:</span>
                                <span>{{ $pedido->cliente_nome ?? 'Nome não disponível' }}</span>
                            </div>

                            <!-- Lista de Produtos -->
                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-box-open me-2"></i>Produtos:</span>
                                <ul>
                                    @foreach($pedido->produtos as $index => $produto)
                                        <li>
                                            <strong>{{ $produto }}</strong><br>
                                            Quantidade: {{ $pedido->quantidades[$index] }}<br>
                                            Preço Individual: R$ {{ number_format($pedido->precos[$index], 2, ',', '.') }}<br>
                                            Observação: {{ $pedido->observacoes[$index] ?? 'Nenhuma observação' }}<br>
                                            Subtotal: R$ {{ number_format($pedido->precos[$index] * $pedido->quantidades[$index], 2, ',', '.') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Entrega:</span>
                                <span>{{ $pedido->endereco }}</span>
                            </div>
                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-cubes me-2"></i>Total de Itens:</span>
                                <span>{{ array_sum($pedido->quantidades) }} unidades</span>
                            </div>

                            <div class="info-group mb-2">
                                <span class="fw-bold"><i class="fas fa-dollar-sign me-2"></i>Total:</span>
                                <span>R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                            </div>

                            <div class="info-group mb-3">
                                <span class="fw-bold"><i class="fas fa-info-circle me-2"></i>Status:</span>
                                <span>{{ $pedido->status }}</span>
                            </div>

                            <div class="mt-auto text-center">
                                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>Editar Pedido
                                </a>
                                <a href="#" class="btn btn-danger btnRemover" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('pedidos.destroy', $pedido->id) }}">
                                   <i class="fas fa-trash-alt me-1"></i>Remover
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-warning text-center">Nenhum pedido encontrado.</div>
            </div>
        @endif
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar para o Menu
        </a>
    </div>
</div>

<!-- Modal de Confirmação para Remoção -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Remoção de Pedido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja realmente remover este pedido?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="post" action="">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar o Flatpickr em português -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuração do Flatpickr para os campos de data
        flatpickr("#data_inicio, #data_fim", {
            dateFormat: "d/m/Y",
            locale: flatpickr.l10ns.pt, // Definindo o idioma para português
            maxDate: new Date()
        });

        // Configuração do formulário de remoção no modal
        document.querySelectorAll('.btnRemover').forEach(function(button) {
            button.addEventListener('click', function() {
                const action = button.getAttribute('data-action');
                document.getElementById('deleteForm').setAttribute('action', action);
            });
        });
    });
    function removerMensagem() {
        const mensagemElement = document.getElementById('mensagem');
        if (mensagemElement) {
            mensagemElement.remove();
        }
    }

    // Remover a mensagem automaticamente após 6 segundos
    setTimeout(removerMensagem, 6000);
</script>

@endsection
