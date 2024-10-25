@if($headers['nacional'])
<table class="table small_body" style="color:#A5A5A5; width: 100%;" width="100%">
    <tr>
        <td style="width: 70%;background: #337ab7;color: white;">
            <strong>"Nacional"</strong>
        </td>
        <td style="width: 30%; text-align: center; background: #EDF5FC;" rowspan="2">{{ $retailerProduct->retailer->name }}</td>
    </tr>
    <tr>
        <td style="width: 70%; background: #EDF5FC;"></td>
    </tr>
    <tr>
        <td style="width: 70%;background: #337ab7;color: white;text-align: left;">Datos Generales</td>
        <td style="width: 30%;background: #337ab7;color: white;">Gráfica</td>
    </tr>
    <tr>
        <td style="width: 70%;" class="position-left">
            @if($format === 'pdf')
                <br /><br /><br /><br />
            @endif
            <table class="table-bordered table-hover gridview small_body" style="color:#A5A5A5; width: 100%;" width="100%">
                <tr style="font-weight: bold;">
                    <td style="background: #337ab7;color: white;">Sucursal</td>
                    @var $Nproducts = []
                    @var $i         = 1
                    
                    @foreach ($headers['nacional'] as $keyN => $value)
                        @if($i == 1)
                            @foreach ($value as $keyN2 => $value2)
                                <td style="background: #337ab7;color: white;">{{$keyN2}}</td>
                                @var $Nproducts[$keyN2]=0
                            @endforeach
                        @endif
                        @var $i++
                    @endforeach
                    <td style="background: #337ab7;color: white;">Meta Total</td>
                    <td style="background: #337ab7;color: white;">Total Ejecutado</td>
                    <td style="background: #337ab7;color: white;">% Logrado</td>
                </tr>
                
                @var $Nmeta_total               = 0
                @var $Ntotal_ejecutado          = 0
                @var $Nmeta_total_header        = 0
                @var $Ntotal_ejecutado_header   = 0
              
                @foreach ($headers['nacional'] as $keyN => $value)

                    <tr style="{{($format == 'pdf')?'background-color: #eef9f8;':''}}">
                        <td style="text-align: left;">{{$keyN}}</td>
                        
                        @var $meta_total        = 0;
                        @var $total_ejecutado   = 0;
                        
                        @foreach ($value as $keyN2 => $value2)
                            @if($value2)
                                <td>{{$value2['total_ejecutado']}}</td>
                                @var $meta_total+=$value2['meta_total'];
                                @var $total_ejecutado+=$value2['total_ejecutado'];
                                @var $Nproducts[$keyN2]+=$value2['total_ejecutado']
                            @else
                                <td>0</td>
                                @var $Nproducts[$keyN2]+=0
                            @endif
                        @endforeach
                        <td>{{$meta_total}}</td>
                        <td>{{$total_ejecutado}}</td>
                        @var $percentage = round(($total_ejecutado*100)/$meta_total)
                        <td>
                            <div class="progress progress-rounded">
                                <div class="progress-bar {{getProcessLoaders($percentage)}}" style="width: {{$percentage}}%;text-align: left;">
                                    &nbsp;&nbsp;&nbsp;<span>{{$percentage}} % </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @var $Nmeta_total_header+=$meta_total
                    @var $Ntotal_ejecutado_header+=$total_ejecutado
                @endforeach
                
                <tr style="font-weight: bold;">
                    <td style="background: #337ab7;color: white;"></td>
                    
                    @foreach ($Nproducts as $Nkey_total => $value_total)
                       <td style="background: #337ab7;color: white;">{{$value_total}}</td>
                    @endforeach
                    
                    <td style="background: #337ab7;color: white;">{{$Nmeta_total_header}}</td>
                    <td style="background: #337ab7;color: white;">{{$Ntotal_ejecutado_header}}</td>
                    
                    @var $percentage_header = round(($Ntotal_ejecutado_header*100)/$Nmeta_total_header)
                    
                    <td style="background: #337ab7;color: white;">
                        <div class="progress progress-rounded">
                            <div class="progress-bar {{getProcessLoaders($percentage_header)}}" style="width: {{$percentage_header}}%;text-align: left;">
                                &nbsp;&nbsp;&nbsp;<span>{{$percentage_header}} % </span>
                            </div>
                        </div>
                    </td>
                </tr>

            </table>
        </td>
        <td style="width: 30%;">
            @var $idGraph = 'nacional'
            @var $idGraphUser = uniqid()
            @if($format !== 'pdf')
                <div class="panel-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="graphic_sucursal_{{$idGraph}}"></div>
                    </div>
                </div>
                @include('report.graphic.partials.script', ['type'=>1, 'percentage'=>$percentage_header])
            @else
                @if($percentage_header<=100)
                    <img src="{{ base_path('public/images/percentages/Porcentaje Logrado('.$percentage_header.').png') }}" width="230">
                @else
                    <img src="{{ base_path('public/images/percentages/Porcentaje Logrado(xx).png') }}" width="230">
                    <center style="text-align: center;font-size: 12px; color: #D87A80;"><strong>{{$percentage_header}}%</strong></center>
                @endif
            @endif
        </td>
    </tr>
</table>
@endif