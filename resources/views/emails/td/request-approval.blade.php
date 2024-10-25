<div class="container">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <h3>
        <span class="label label-primary">
            Solicitud de aprobacion a caso facultativo MR - {{ $data['header']->issue_number }}
        </span>
            </h3>

            <div class="alert alert-danger" role="alert">
                <strong>Observaciones en la solicitud del seguro:</strong>
                <br>
                {{ $data['header']->facultative_observation }}
            </div>

            <a href="{{ route('home', ['odh' => encode($data['header']->id)]) }}" class="btn btn-success btn-lg">Procesar
                caso facultativo</a>
        </div>
        <div class="col-md-2">

        </div>
    </div>
</div>
