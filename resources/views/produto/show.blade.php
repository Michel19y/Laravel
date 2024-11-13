

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show de Produto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <link rel="icon" type="image" href="/img/p.png">
    <style>
       

    </style>
</head>

<body>
    <div class="container mt-5 mx-auto" style="max-width: 600px;">
        <h1 class="text-center mb-4">Detalhes do Produto</h1>
        <div class="mb-3">
            <label for="id-input-id" class="form-label">ID:</label>
            <input type="text" class="form-control" id="id-input-id" disabled value="{{ $produto->id }}">
        </div>
        <div class="mb-3">
            <label for="id-input-id" class="form-label">Foto:</label>
      <center>  <img src="   {{ $produto->urlImage }}"> </center>
        </div>
        <div class="mb-3">
            <label for="id-input-nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="id-input-nome" disabled value="{{ $produto->nome }}">
        </div>
        <div class="mb-3">
            <label for="id-input-preco" class="form-label">Preço:</label>
            <input type="text" class="form-control" id="id-input-preco" disabled value="{{ $produto->preco }} €">
        </div>
        <div class="mb-3">
            <label for="id-input-descricao" class="form-label">Tipo de Produto:</label>
            <input type="text" class="form-control" id="id-input-descricao" disabled value="{{ $produto->descricao }}">
        </div>
        <div class="mb-3">
            <label for="id-input-ingredientes" class="form-label">Ingredientes:</label>
            <input type="text" class="form-control" id="id-input-ingredientes" disabled value="{{ $produto->ingredientes }}">
        </div>
        <div class="mb-3">
            <label for="id-input-updated_at" class="form-label">Última Atualização:</label>
            <input type="text" class="form-control" id="id-input-updated_at" disabled value="{{ $produto->updated_at }}">
        </div>
        <div class="mb-3">
            <label for="id-input-created_at" class="form-label">Data de Criação:</label>
            <input type="text" class="form-control" id="id-input-created_at" disabled value="{{ $produto->created_at }}">
        </div>
        <div class="text-center">
            <a href="{{url()->previous()}}" class="btn btn-vibrant">Voltar</a>
        </div>
    </div>
</body>

</html>
