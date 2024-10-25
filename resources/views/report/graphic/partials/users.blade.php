@foreach ($Svalue as $key2 => $value2)
    <table class="table small_body" style="color:#A5A5A5; width: 100%;" width="100%">
        <tr>
            <td style="width: 70%;background: #1EA5CC;color: white;text-align: left;">Agencia <strong>"{{$key2}}"</strong></td>
            <td style="width: 30%;background: #1EA5CC;color: white;">Gráfica</td>
        </tr>
        <tr>
            <td style="width: 70%;" class="position-left">
                @if($format === 'pdf')
                    <br /><br /><br /><br />
                @endif
                <table class="table-bordered table-hover gridview small_body" style="color:#A5A5A5; width: 100%;" width="100%">
                    <tr style="font-weight: bold;">
                        <td style="background: #1EA5CC;color: white;">Funcionario</td>
                        @var $Uproducts = []
                        @var $i = 1;
                        @foreach($headers['users'][$Skey][$key2] as $keyU => $valueU)
                            @if($i == 1)
                                @foreach($valueU as $keyU2 => $valueU2)
                                    <td style="background: #1EA5CC;color: white;">{{$keyU2}}</td>
                                    @var $Uproducts[$keyU2]=0
                                @endforeach
                            @endif
                            @var $i++
                        @endforeach
                        <td style="background: #1EA5CC;color: white;">Meta Total</td>
                        <td style="background: #1EA5CC;color: white;">Total Ejecutado</td>
                        <td style="background: #1EA5CC;color: white;">% Logrado</td>
                    </tr>
                    @var $Umeta_total               = 0
                    @var $Utotal_ejecutado          = 0
                    @var $Umeta_total_header        = 0
                    @var $Utotal_ejecutado_header   = 0
                    @var $percentage_header   = 0

                    @foreach ($headers['users'][$Skey][$key2] as $keyU => $valueU)
                        @var $name = explode('-',$keyU)
                        <tr style="{{($format == 'pdf')?'background-color: #eef9f8;':''}}">
                            <td style="text-align: left;">{{$name[1]}}</td>

                            @var $total_ejecutado   = 0;
                            
                            @foreach ($valueU as $keyU2 => $valueU2)
                                @if($valueU2)
                                    <td>{{count($valueU2)}}</td>
                                    
                                    @var $total_ejecutado+=count($valueU2);
                                    @var $Uproducts[$keyU2]+=count($valueU2)
                                @else
                                    <td>0</td>
                                    @var $Uproducts[$keyU2]+=0
                                @endif
                            @endforeach
                            <td>{{$meta_venta}}</td>
                            <td>{{$total_ejecutado}}</td>
                            @var $percentage = round(($total_ejecutado*100)/$meta_venta)
                            <td>
                                <div class="progress progress-rounded">
                                    <div class="progress-bar {{getProcessLoaders($percentage)}}" style="width: {{$percentage}}%;text-align: left;">
                                        &nbsp;<span>{{$percentage}}%</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @var $Umeta_total_header+=$meta_venta
                        @var $Utotal_ejecutado_header+=$total_ejecutado
                    @endforeach

                    <tr style="font-weight: bold;">
                        <td style="background: #1EA5CC;color: white;"></td>

                        @foreach ($Uproducts as $Ukey_total => $value_total)
                           <td style="background: #1EA5CC;color: white;">{{$value_total}}</td>
                        @endforeach

                        <td style="background: #1EA5CC;color: white;">{{$Umeta_total_header}}</td>
                        <td style="background: #1EA5CC;color: white;">{{$Utotal_ejecutado_header}}</td>

                        @var $percentage_header = round(($Utotal_ejecutado_header*100)/$Umeta_total_header)

                        <td style="background: #1EA5CC;color: white;">
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
                @var $idGraph = preg_replace('([^A-Za-z0-9])', '', $Skey).'_'.preg_replace('([^A-Za-z0-9])', '', $key2)
                @var $idGraphUser = preg_replace('([^A-Za-z0-9])', '', $Skey).'_'.preg_replace('([^A-Za-z0-9])', '', $key2)
                @if($format !== 'pdf')
                    <div class="panel-body">
                        <div class="chart-container">
                            <div class="chart has-fixed-height" id="graphic_user_{{$idGraphUser}}_{{$idGraph}}"></div>
                        </div>
                    </div>
                    @include('report.graphic.partials.script', ['type'=>2, 'percentage'=>$percentage_header])
                @else
                    @if($percentage_header<=100)
                        <img src="{{ base_path('public/images/percentages/Porcentaje Logrado('.$percentage_header.').png') }}" width="230">
                    @else
                        <img src="{{ base_path('public/images/percentages/Porcentaje Logrado(xx).png') }}" width="230">
                        <center style="text-align: center;font-size: 12px; color: #D87A80;"><strong>{{$percentage_header}}%</strong></center>
                    @endif
                @endif
                
                <table class="table-bordered table-hover gridview small_body" style="color:#A5A5A5; width: 100%;" width="100%">
                    <tr style="font-weight: bold;">
                        <td style="width: 20%;background: #1EA5CC;color: white;">META</td>
                        <td style="width: 20%;background: #1EA5CC;color: white;">VENTAS</td>
                        <td style="width: 20%;background: #1EA5CC;color: white;">TOTAL</td>
                        <td style="width: 20%;background: #1EA5CC;color: white;">VARIACION</td>
                        <td style="width: 20%;background: #1EA5CC;color: white;">%</td>

                    </tr>
                    <tr style="{{($format == 'pdf')?'background-color: #eef9f8;':''}}">
                        <td style="width: 20%;">{{$Umeta_total_header}}</td>
                        <td style="width: 20%;">{{$Utotal_ejecutado_header}}</td>
                        <td style="width: 20%;">{{$Utotal_ejecutado_header}}</td>
                        <td style="width: 20%;">{{$Umeta_total_header-$Utotal_ejecutado_header}}</td>
                        <td style="width: 20%;">{{$percentage_header}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endforeach