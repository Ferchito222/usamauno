@extends('body')
@var $step=0
@section('body')

    <div class="col-xs-12" ng-controller="CancellationController">
        <h6>Anulación de Certificados</h6>
        <hr />
        {!! Form::open(['url' => request()->fullUrl(),
                                'method' => 'get',
                                'class'  => 'form-horizontal'
                            ]) !!}
          <div class="col-xs-12 col-md-12">
            @include('report.partials.inputs-search')
          </div>
        {!! Form::close() !!}
        <div class="clearfix">
            :
        </div>
        <div style="width: 100%;overflow: scroll;">
            <table class="table-bordered table-hover gridview small_body" width="200%" border="1" >
                <thead>
                  <tr>
                      <th>NRO. DE PÓLIZA</th>
                      <th>CLIENTE</th>
                      <th>C.I.</th>
                      <th>Nro TARJETA</th>
                      <th>TIPO TARJETA</th>
                      <th>CLIENTE TOMADOR</th>
                      <th>PRIMA TOTAL</th>
                      <th>FORMA DE PAGO</th>
                      <th>PERIODICIDAD</th>
                      <th>USUARIO</th>
                      <th>SUCURSAL / AGENCIA</th>
                      <th>ACCIÓN</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($headers as $header)
                    @foreach ($header->details as $detail)
                      <tr>
                          <td style="font-weight: bold;text-align: left;">{{ $header->prefix }} - {{ $header->issue_number }}</td>
                          <td>{{ $detail->client->full_name }}</td>
                          <td>{{ $detail->client->dni }} {{ $detail->client->extension }}</td>
                          <td>************{{ $detail->card_number }}</td>
                          <td>{{ config('base.account_number_types.'.$detail->card) }}</td>
                          <td>{{ $detail->holder==1?'SI':'NO' }}</td>
                          <td>{{ $header->total_premium }} {{ $header->currency }}</td>
                          <td>{{ config('base.payment_methods_vi.'.$header->payment_method) }}</td>
                          <td>{{ config('base.periods.'.$header->period) }}</td>

                          <td>{{ $header->user->full_name }}</td>
                          <td>
                            {{ ! is_null($header->user->city) ? $header->user->city->name : '' }}
                            {{ ! is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                          </td>
                          <td>
                            <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.cancel.create', ['rp_id' => $rp_id, 'header_id' => encode($header->id)]) }}" 
                                               ng-click="cancelCreate($event)">
                                              Anular Certificado
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('certificate.show', [
                                                      'code' => $retailerProduct->companyProduct->product->code,
                                                      'rp_id' => $rp_id,
                                                      'type' => 'certificate',
                                                      'header_id' => encode($header->id),
                                                      'format' => 'pdf',
                                              ])}}" target="_blank">
                                              Ver Certificado de Emisión
                                            </a>
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
              <br>
              <br>
        </div>    
        <br />
        <div style="text-align: center;" class="small_body">
            {!! $headers->appends(request()->all())->render() !!}
        </div>
        
    </div>
      
@endsection
