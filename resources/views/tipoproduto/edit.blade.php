<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit de TipoProduto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image" href="/img/p.png">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
</head>

<body>
    <div class="container">
        <form method="post" action="{{ route('tipoproduto.update', $tipoProduto->id) }}">
            @csrf
            @method("put")
            <div class="mb-3">
                <label for="id-input-id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id-input-id" aria-describedby="id-help" disabled
                    value="{{ $tipoProduto->id }}">
                <div id="id-help" class="form-text">Não é necessário informar o ID para editar um dado.</div>
            </div>
            <div class="mb-3">
                <label for="id-input-descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" id="id-input-descricao" name="descricao"
                    placeholder="Digite a descricao do produto" value="{{ $tipoProduto->descricao }}">
            </div>
            <div class="mb-3">
                <a href="{{url()->previous()}}" class="btn btn-vibrant">Voltar</a>
               <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</body>

</html>
