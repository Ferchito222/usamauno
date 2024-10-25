<page><div style="page-break-before: always;">&nbsp;</div></page>
<p style="color:#A5A5A5;">Resumen de Mejores Ventas</p>
<table class="table small_body" style="color:#A5A5A5; width: 100%;" width="100%">
    <tr>
        <td style="width: 50%;background: #337ab7;color: white;text-align: left;">Funcionarios con Mayores Ventas</td>
        <td style="width: 50%;background: #337ab7;color: white;text-align: left;">Agencias con Mayores Ventas</td>
    </tr>
    <tr>
        <td style="width: 50%;" class="position-left">
            <br /><br />
            <p style="color:#A5A5A5;">VENTAS A NIVEL NACIONAL</p>
            <table class="table-bordered table-hover gridview small_body" style="color:#A5A5A5; width: 100%;" width="100%">
                <tr style="font-weight: bold;">
                    <td style="background: #337ab7;color: white;"></td>
                    <td style="background: #337ab7;color: white;">Funcionario</td>
                    <td style="background: #337ab7;color: white;">Agencia</td>
                    <td style="background: #337ab7;color: white;">Sucursal</td>
                    <td style="background: #337ab7;color: white;">Total Ventas</td>
                </tr>
                @var $i = 1
                @foreach ($maxHeaders['users'] as $key => $value)
                    @if($i<=5)
                        @var $data = explode('-',$key)
                        <tr style="{{($format == 'pdf')?'background-color: #eef9f8;':''}}">
                            <td style="text-align: left;">{{$i}}</td>
                            <td style="text-align: left;">{{$data[1]}}</td>
                            <td>{{$data[2]}}</td>
                            <td>{{$data[3]}}</td>
                            <td>{{$value}}</td>
                        </tr>
                    @endif
                    @var $i++
                @endforeach
            </table>
        </td>
        <td style="width: 30%;">
            <br /><br />
            <p style="color:#A5A5A5;">VENTAS A NIVEL NACIONAL</p>
            <table class="table-bordered table-hover gridview small_body" style="color:#A5A5A5; width: 100%;" width="100%">
                <tr style="font-weight: bold;">
                    <td style="background: #337ab7;color: white;"></td>
                    <td style="background: #337ab7;color: white;">Agencia</td>
                    <td style="background: #337ab7;color: white;">Sucursal</td>
                    <td style="background: #337ab7;color: white;">Total Ventas</td>
                </tr>
                @var $i = 1
                @foreach ($maxHeaders['agencies'] as $key => $value)
                    @if($i<=5)
                        @var $data = explode('-',$key)
                        <tr style="{{($format == 'pdf')?'background-color: #eef9f8;':''}}">
                            <td style="text-align: left;">{{$i}}</td>
                            <td style="text-align: left;">{{$data[1]}}</td>
                            <td>{{$data[2]}}</td>
                            <td>{{$value}}</td>
                        </tr>
                    @endif
                    @var $i++
                @endforeach
            </table>
        </td>
    </tr>
</table>