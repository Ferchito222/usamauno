@var $arr_step['au'] = [1 => 'Datos del Prestamo y Cliente',2 => 'Datos del Vehículo',3 => 'Resultado Cotización',4 => 'Emisión de la Póliza de Automotores',5 => 'Impresión de la Póliza']
@var $arr_step['td'] = [1 => 'Datos del Cliente',2 => 'Datos del Interes Asegurado',3 => 'Resultado Cotización',4 => 'Emisión de Póliza Seguro de Multiriesgo',5 => 'Impresion de la Póliza']
@var $arr_step['ema'] = [1 => 'Datos del Cliente',2 => 'Datos del Interes Asegurado',3 => 'Resultado Cotización',4 => 'Emisión de Póliza Seguro de Equipo y Maquinaria de Contratistas',5 => 'Impresion de la Póliza']
@var $arr_step['rma'] = [1 => 'Datos del Cliente',2 => 'Datos del Interes Asegurado',3 => 'Resultado Cotización',4 => 'Emisión de Póliza Seguro de Rotura de Maquinaria',5 => 'Impresion de la Póliza']
<div class="panel-heading divhr">
    <div class="steps-basic2 wizard">
        <div class="steps">
            <ul>
                @foreach ($arr_step[$product] as $key => $value)
                    @if($step==$key)
                        <li class="current">
                    @elseif($key < $step)
                        <li class="first done">
                    @else
                        <li class="disabled last">
                    @endif
                        <a href="#">
                            @if($step==$key)
                                <span class="current-info audible">current step: </span>
                            @endif
                            <span class="number">{{$key}}</span> {{$value}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
            title="Detalle de producto" data-placement="right" data-toggle="modal"
            data-target="#modal_theme_primary">
        <i class="icon-question7"></i> Producto
    </button>
</div>