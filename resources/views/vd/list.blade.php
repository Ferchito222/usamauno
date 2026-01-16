@extends('body')
@var $step=1
@section('body')

    @if(is_null($header))
        <div class="alert bg-danger alert-styled-right">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
            </button>
            <span class="text-semibold">La Cotización no existe</span>.
        </div>
    @endif
    
    @if(! is_null($header))
        <div class="col-xs-12">
            <br>
            <div class="text-right">
                @if($header->details->count() > 0 || $header->details->count() > 1)
                    @if($header->questions)
                        <a class="btn btn-primary"
                           href="{{ route($retailerProduct->companyProduct->product->code.'.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}">
                            Continuar <i class="icon-arrow-right14 position-right"></i>
                        </a>
                    @endif
                @endif
            </div>
            <br>
        </div>
        <table class="table small_body">
            @if($header->details->count() > 0)
                <thead>
                <tr style="background: #f3f3f3 none repeat scroll 0 0; text-transform: uppercase;">
                    <th>Cliente</th>
                    <th>C.I.</th>
                    <th>Nombres y Apellidos</th>
                    <th>Fecha Nacimiento</th>
                    <th class="text-center">Accion</th>
                </tr>
                </thead>
                <tbody>
                @foreach($header->details as $key => $detail)
                    <tr>
                        <td style="background: #f3f3f3 none repeat scroll 0 0; text-transform: uppercase;">{{ $key === 0 ? 'T' : 'C' . $key }}</td>
                        <td>
                            <a href="#">
                                {{ $detail->client->dni
                                    . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                                {{ $detail->client->extension }}
                            </a>
                        </td>
                        <td>{{ $detail->client->full_name }}</td>
                        <td>{{ dateToFormat($detail->client->birthdate) }}</td>

                        <td class="text-center">
                            <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.detail.edit', [
                                                'rp_id'     => $rp_id,
                                                'header_id' => $header_id,
                                                'detail_id' => encode($detail->id)
                                                ]) }}">
                                                <i class="icon-plus2"></i> Editar datos</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @endif
        </table>
    @endif
@endsection
