<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Migration</title>

    {!! Html::style('assets/css/bootstrap.css') !!}
</head>
<body>
<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <div class="">
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="content-wrapper">
            <div class="panel text-center">
                {!! Form::open(['route' => ['migration::export'],
                    'method' => 'post',
                    'class'  => 'form-horizontal',
                ]) !!}

                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Módulo</label>

                            <div class="form-group-lg"
                                 style="font-size: 120%; font-weight: bold; padding-left: 50px; text-align: justify;">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="types[]" value="users"> Usuarios
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="types[]" value="clients"> Clientes
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="types[]" value="elp"> Ecovida / Ecoprotección
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Exportar</button>
                    </div>
                    <div class="col-lg-3"></div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</body>
</html>