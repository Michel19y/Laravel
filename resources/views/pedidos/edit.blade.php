@extends('layouts.on')

<style>
    /* Estilos Gerais */
    .container {
        max-width: 800px;
    }

    h2.text-center {
        color: #28a745;
        font-weight: bold;
    }

    /* Formulário */
    form {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 4px rgba(40, 167, 69, 0.2);
    }

    /* Botões */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        color: #fff;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #565e64;
    }

    /* Campos de Produto */
    .product-group {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
        margin-top: 15px;
    }

    .product-group:first-of-type {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }
</style>

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Pedido #{{ $pedido->id }}</h2>

    <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if(Auth::guard('admin')->check())
 
    
        <!-- Campo de seleção para o status do pedido -->
        <div class="mb-4">
            <label for="status" class="form-label">Status do Pedido <i class="fas fa-arrow-down me-2"></i>
            </label>
            <select name="status" id="status" class="form-control">
                <option value="p" {{ $pedido->status == 'p' ? 'selected' : '' }}>Pendente</option>
                <option value="a" {{ $pedido->status == 'a' ? 'selected' : '' }}>Aprovado</option>
                <option value="c" {{ $pedido->status == 'c' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>      
    @endif

        <!-- Loop para listar e editar os produtos do pedido -->
        @foreach ($pedido->produtos as $produto)
        <div class="product-group mb-3">
            <label class="form-label">Produto: {{ $produto->nome }}  (Quantidade): </label>
            <input type="number" name="produtos[{{ $produto->id }}][quantidade]" 
                   class="form-control mb-2" value="{{ $produto->pivot->quantidade }}" min="1">
            <label class="form-label">Observação</label>
            <input type="text" name="produtos[{{ $produto->id }}][observacao]" 
                   class="form-control" value="{{ $produto->pivot->observacao }}">
        </div>
        @endforeach

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection


