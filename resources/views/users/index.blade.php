<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Celke</title>
</head>
<body>

    <div class="container">
        <div class="card my-5 border-light shadow">
            <h3 class="card-header">Laravel 11 - Importar CSV</h3>

            @session('success')
                    <div class="alert alert-success" role="alert">{!! $value !!}</div>
            @endsession

            @session('error')
                <div class="alert alert-danger" role="alert">{!! $value !!}</div>
            @endsession

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>
            @endif

            <form action="{{ route('users.import')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="input-group my-4">
                    <input type="file" name="file" class="form-control" id="file" accept=".csv">
                    <button type="submit" class="btn btn-outline-success" id="fileBtn">
                        <i class="fa-solid fa-upload"></i> Importar
                    </button>    

                </div>


            </form>

            

        </div>

    </div>
    
</body>
</html>