<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Migration Users</title>

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
                        <div class="row">

                            {!! Form::open(['route' => ['migration::users.read.xls'], 
                            'method' => 'post', 
                            'files' => true, 
                            'class' => 'form-horizontal']) !!}
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label class="col-lg-5 control-label ">XLS usuarios: </label>
                                    <div class="col-lg-7">
                                        <input type="file" class="file-styled" name="file" id="file">
                                    </div>

                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('file') }}</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    {!! Form::button('Importar <i class="icon-upload"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>