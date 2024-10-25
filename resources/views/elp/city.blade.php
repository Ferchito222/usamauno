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
        <td colspan="2" id="titulo">Ecoseguro {{ $namecity }}</td>
    </tr>

    <tr id="cabecera">
        <td style="width: 20px;">Fecha de Transaccion</td>
        <td style="width: 12px;">Monto Total</td>
    </tr>

    @var $total = 0;

    @foreach ($datacity as $key => $dataacount)
        @var $prima = $dataacount -> sum('transaction_amount');
        @var $total = $total + $prima;

        <tr id="cuerpo">
            <td>{{ $key }}</td>
            <td>{{ $prima }}</td>
        </tr>
    @endforeach

    <tr id="totales">
        <td>TOTAL</td>
        <td>{{$total}}</td>
    </tr>

</table>
</html>