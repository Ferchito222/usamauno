@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
  
@endsection

@section('content')

<div class="row panel">
    <div class="col-md-12">
        <div class="border-top-primary">
            @include('partials.messages')
            <div class="panel-body">
                <div class="col-xs-12" ng-controller="CancellationController">
                    <h6>Anulación de Certificados</h6>
                    <hr />
                    {!! Form::open(['url' => request()->fullUrl(),
                                            'method' => 'get',
                                            'class'  => 'form-horizontal'
                                        ]) !!}
                      <div class="col-xs-12 col-md-12">
                        @include('report.partials.inputs-search2')
                      </div>
                    {!! Form::close() !!}
                    <div class="clearfix">
                        <small class="help-block">Lista de Certificados Desgravamen:</small>
                    </div>
                    <div style="width: 100%; overflow: scroll;" ng-controller="ReportController">
                        <table width="200%" class="table-bordered table-hover gridview small_body" border="1" >
                      <thead>
                          <tr>
                              <th>NRO. DE CERTIFICADO</th>
                              <th>COBERTURA</th>
                              <th>PRODUCTO</th>
                              <th>CLIENTE</th>
                              <th style="width: 100px;">C.I.</th>
                              <th>MONTO SOLICITADO / MONEDA</th>
                              <th>SALDO DEUDOR</th>
                              <th>TOTAL MONTO ACUMULADO</th>
                              <th>PLAZO DEL CREDITO /<br>TIPO DEL PLAZO</th>
                              <th>TITULAR</th>
                              <th>USUARIO</th>
                              <th>SUCURSAL / AGENCIA</th>
                              <th>FECHA DE INGRESO</th>

                              <th>COBERTURAS</th>
                              <th>EMITIDO</th>
                              <th>FECHA DE EMISIÓN</th>
                              <th>ESTADO COMPAÑIA</th>
                              <th>MOTIVO ESTADO COMPAÑIA</th>
                              <th>ESTADO BANCO</th>
                              <th>FACULTATIVO OBSERVACIÓN</th>
                              <th>FECHA RESPUESTA FINAL COMPAÑIA</th>
                              <th>DIAS EN PROCESO</th>
                              <th>DURACION TOTAL DEL CASO</th>
                              <th>PORCENTAJE EXTRAPRIMA</th>
                              <th>TASA ACTUAL</th>
                              <th>TASA RECARGO</th>
                              <th>NUMERO DE OPERACION</th>

                              <th>ACCIÓN</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($headers as $header)
                          @foreach ($header->details as $index => $detail)
                            <tr>
                                <td style="font-weight: bold;">
                                    {{ explode('-',$header->policy_number)[0] }}-{{ $header->issue_number }}{{$header->version_number}}
                                </td>
                                <td>{{ $header->coverage->name }}</td>
                                <td>{{ $header->creditProduct->name }}</td>
                                <td>{{ $detail->client->full_name }}</td>
                                <td>
                                    {{ $detail->client->dni
                                        . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                                    {{ $detail->client->extension }}
                                </td>
                                <td>{{ number_format($header->amount_requested, 2) }} {{ $header->currency }}.</td>
                                <td>{{ number_format($detail->balance, 2) }} BS.</td>
                                <td>{{ number_format($detail->cumulus, 2) }} BS.</td>
                                <td>{{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}</td>
                                <td>{{ config('base.headlines.' . $detail->headline) }}</td>
                                <td>{{ $header->user->full_name }}</td>
                                <td>{{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                                    /
                                    {{ $header->user->agency->name }}
                                </td>
                                <td>{{ date_format_report($header->created_at) }}</td>
                                
                                <td style=" ">
                                    @foreach($detail->coverages as $coverage)

                                        @if($coverage->coverage == 'CTPD')
                                            @if($coverage->granted == true)
                                            <span style="font-weight: bold">*</span>
                                                {{ config('base.client_coverages.' . $coverage->coverage) }}
                                            @endif
                                        @elseif($coverage->coverage != 'CF')
                                        <span style="font-weight: bold">*</span>
                                            {{ config('base.client_coverages.' . $coverage->coverage) }}
                                        @endif
                                        <br>
                                    @endforeach
                                </td>
                                <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                                <td>{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                                <td>{{ getDeStateCompany($header, $detail) }}</td>
                                <td>{{ getDeReasonStateCompany($header, $detail) }}</td>
                                <td>{{ $header->issued ? 'Emitido' : 'No Emitido' }}</td>
                                <td>{{ getDeObservationFacultative($detail) }}</td>
                                <td>
                                    @if($header->facultative
                                        && (! is_null($detail->facultative)
                                            && $detail->facultative->state === 'PR')
                                    )
                                        {{ $detail->facultative->updated_at->format('d/m/Y H:i') }}
                                    @elseif($header->facultative
                                            && (! is_null($detail->facultative)
                                            && $detail->facultative->state === 'PE'
                                            && $detail->facultative->observations->count() > 0)
                                    )
                                        {{ $detail->facultative->observations->last()->created_at->format('d/m/Y H:i') }}
                                    @endif
                                </td>
                                <td>{{ getDeDaysInProcess($header, $detail) }}</td>
                                <td>{{ getDeTotalDuration($header) }} dias</td>
                                <td>
                                    @if($header->facultative
                                        && ( ! is_null($detail->facultative)
                                            && $detail->facultative->state === 'PR'
                                            && $detail->facultative->approved
                                            && $detail->facultative->surcharge))
                                        {{ $detail->facultative->percentage }} %
                                    @else
                                        0 %
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($detail->rate, 4) }}
                                </td>
                                <td>
                                    @if($header->facultative
                                        && ( ! is_null($detail->facultative)
                                            && $detail->facultative->state === 'PR'
                                            && $detail->facultative->approved
                                            && $detail->facultative->surcharge))
                                        {{ number_format($detail->facultative->final_rate, 4) }}
                                    @else
                                        0.0000
                                    @endif
                                </td>
                                <td>{{ $header->operation_number }}</td>
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
                    </div>
                    <br />
                    <div style="text-align: center;" class="small_body">
                        {!! $headers->appends(request()->all())->render() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection