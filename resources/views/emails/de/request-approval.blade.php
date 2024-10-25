<div class="container">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <h2>
                <span class="label label-default">
                    Sucursal -
                    @if($data['header']->placeIssuance instanceof \Sibas\Entities\City)
                        {{ $data['header']->placeIssuance->name }}
                    @else
                        {{ $data['header']->user->city->name }}
                    @endif
                </span>
            </h2>
            <h3>
                <span class="label label-primary">
                    Solicitud de aprobacion a caso facultativo: Certificado DE - {{ $data['header']->issue_number }}
                </span>
            </h3>
            <h3>
                <span class="label label-primary">
                    Número de Póliza: {{ $data['header']->policy_number }}
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
