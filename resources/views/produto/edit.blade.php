
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image" href="/img/p.png">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
</head>

<body>
    <div class="container">
        <h1>Editar Produto</h1>
        <form method="POST" action="{{ route('produto.update', $produto->id) }}" enctype="multipart/form-data">
            @csrf
            @method("put")
            <div class="mb-3">
                <label for="id-input-id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id-input-id" disabled value="{{ $produto->id }}">
                <div class="form-text">Não é necessário informar o ID para editar um dado.</div>
            </div>
            <div class="mb-3">
                <label for="id-input-nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="id-input-nome" name="nome" placeholder="Digite o nome do produto" value="{{ $produto->nome }}">
            </div>
            <div class="mb-3">
                <label for="id-input-preco" class="form-label">Preço</label>
                <input type="number" class="form-control" id="id-input-preco" name="preco" placeholder="Digite o preço do produto" step=".01" value="{{ $produto->preco }}">
            </div>
            <div class="mb-3">
                <label for="id-input-Tipo_Produtos_id" class="form-label">Tipo</label>
                <select name="Tipo_Produtos_id" class="form-select">
                    @foreach ($tipoProdutos as $tipoProduto)
                        <option value="{{ $tipoProduto->id }}" {{ $tipoProduto->id == $produto->Tipo_Produtos_id ? 'selected' : '' }}>
                            {{ $tipoProduto->descricao }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id-input-ingredientes" class="form-label">Ingredientes</label>
                <input type="text" class="form-control" id="id-input-ingredientes" name="ingredientes" placeholder="Digite os ingredientes do produto" value="{{ $produto->ingredientes }}">
            </div>
            <div class="mb-3">
                <label for="id-input-imagem" class="form-label">Imagem</label>
                <input type="file" class="form-control" id="id-input-imagem" name="imagem">
            </div>
            <div class="mb-3 text-center">
                   <a href="{{url()->previous()}}" class="btn btn-vibrant">Voltar</a> 
                   <button type="submit" class="btn btn-vibrant">Enviar</button>
            </div>
        </form>
    </div>
</body>

</html>
