
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastrar Produto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="icon" type="image" href="/img/p.png">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <style>
        h1 {
            margin-top: 50px;
        }
    </style>
</head>

<body data-bs-theme="dark">

    <center>
        <h1>Cadastrar Produto</h1>
    </center>

    <div class="container mt-5">
        <form method="POST" action="{{ route('produto.store')}}" enctype="multipart/form-data">
            @csrf
            <!-- Campo de ID (desativado) -->
            <div class="mb-3">
                <label for="id-input-id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id-input-id" aria-describedby="id-help" disabled value="#">
                <div id="id-help" class="form-text">Não é necessário informar o ID para cadastrar um novo dado.</div>
            </div>

            <!-- Campo de Nome -->
            <div class="mb-3">
                <label for="id-input-nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="id-input-nome" placeholder="Digite o Nome" required name="nome">
            </div>

            <!-- Campo de Preço -->
            <div class="mb-3">
                <label for="id-input-preco" class="form-label">Preço</label>
                <input type="number" class="form-control" id="id-input-preco" placeholder="Digite o Preço" required name="preco">
            </div>

            <!-- Campo de Tipo (modificado para Select) -->
            <div class="mb-3">
                <label for="id-select-tipo" class="form-label">Tipo de Produto</label>
                <select class="form-select" id="id-select-tipo" required name="Tipo_Produtos_id">
                    @foreach ($tipoProduto as $item)
                        <option value="{{ $item->id }}">{{ $item->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Campo de Ingredientes -->
            <div class="mb-3">
                <label for="id-input-ingredientes" class="form-label">Ingredientes</label>
                <input type="text" class="form-control" id="id-input-ingredientes" placeholder="Digite os Ingredientes" required name="ingredientes">
            </div>

            <!-- Campo de URL da Imagem -->
            <div class="mb-3">
                <label for="id-input-imagem" class="form-label">Imagem</label>
                <input type="file" class="form-control" id="id-input-urlImage"  name="imagem">
            </div>

            <!-- Botões de Enviar e Voltar -->
            <div class="d-flex justify-content-end mt-3">   <a href="{{url()->previous()}}" class="btn btn-vibrant">Voltar</a>
                <button type="submit" class="btn btn-vibrant">Enviar</button>
              </div>
             
        </form>
    </div>

</body>

</html>
