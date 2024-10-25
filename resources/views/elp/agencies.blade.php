<style>
    table {
        font-family: "Georgia", Times, serif;
        font-size: 10px;
        width: auto;
    }

    #titulo{
        font-size: 15px;
        font-weight: bold;
        text-align: center;
    }

    #cabecera{
        background: #1462A6;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
    }

    #cuerpo{
        background: #B4D6F3;
        text-align: center;
    }

    #totales{
        background: #2a5d8B;
        font-weight: bold;
        text-align: center;
        color: #FFFFFF;
    }


</style>
<html>
    <table>

        <tr>
            <td colspan="3" id="titulo">{{ $titulo }} {{ $date }}</td>
        </tr>

        <tr id="cabecera">
            <td style="width: 35px;">Oficina</td>
            <td style="width: 18px;">Cantidad Vendida</td>
            <td style="width: 16px;">Prima Cobrada Bs</td>
        </tr>

        @var $total = 0;
        @var $total_prima = 0;

            @foreach ($masivo as $datamasivo)
                @var $cantidad = $datamasivo -> headers -> count();
                @var $total = $total + $cantidad;
                @var $prima = $datamasivo -> headers -> sum(function($header){ return($header -> collections -> sum('transaction_amount')); });
                @var $total_prima = $total_prima + $prima;

                <tr id="cuerpo">
                    <td>{{ $datamasivo -> name }}</td>
                    <td> {{$cantidad}} </td>
                    <td>{{ $prima }}</td>
                </tr>
            @endforeach

        <tr id="totales">
            <td>TOTAL</td>
            <td>{{$total}}</td>
            <td>{{$total_prima}}</td>
        </tr>
    </table>

</html>


