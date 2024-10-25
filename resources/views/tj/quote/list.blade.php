@extends('body')
@var $step=0
@section('body')

    <div class="col-xs-12">
        <h6>Emisión de Solicitudes</h6>
        <hr />
        {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.issue.lists', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
        <div class="col-xs-12 col-md-12">
            @include('report.partials.inputs-search', ['type' => 'Q'])
        </div>
        {!! Form::close() !!}

        <table class="table datatable-fixed-left table-striped small_body" width="100%">
            <thead>
            <tr>
                <th>NRO DE COTIZACIÓN</th>
                <th>CLIENTE</th>
                <th>C.I.</th>
                <th>USUARIO</th>
                <th>SUCURSAL / AGENCIA</th>
                <th>FECHA DE INGRESO</th>
                <th>ACCIÓN</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($headers as $header)
                @foreach ($header->details as $detail)
                    <tr>
                        <td>{{ $header->quote_number }}</td>
                        <td>{{ $detail->client->full_name }}</td>
                        <td>{{ $detail->client->dni }} {{ $detail->client->extension }}</td>
                        <td>{{ $header->user->full_name }}</td>
                        <td>
                            {{ ! is_null($header->user->city) ? $header->user->city->name : '' }}
                            {{ ! is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                        </td>
                        <td>
                            {{ $header->created_date }}
                        </td>
                        <td>
                            <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            @if ($header->days_from_creation > $parameter->expiration)
                                                <a href="#">
                                                    <span class="label label-danger">Fecha limite de emisión caducada</span>
                                                </a>
                                            @else
                                                <a href="{{ route($retailerProduct->companyProduct->product->code.'.result', ['rp_id' => $rp_id, 'header_id' => encode($header->id)]) }}">
                                                    <i class="icon-database-edit2"></i> Emitir Solicitud
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
