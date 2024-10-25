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


</style>
<html>

<table>

    <tr>
        <td colspan="9" id="titulo">Liquidación {{ $date }}</td>
    </tr>

    <tr id="cabecera">
        <td style="width: 30px;">Nombres</td>
        <td style="width: 15px;">Paterno</td>
        <td style="width: 15px;">Materno</td>
        <td style="width: 10px;">Cliente</td>
        <td style="width: 20px;">Nro Cuenta</td>
        <td style="width: 15px;">Sucursal</td>
        <td style="width: 30px;">Agencia</td>
        <td style="width: 30px;">AFI_Fincionario</td>
        <td style="width: 15px;">Prima</td>
    </tr>

    @foreach ($months as $oldmonth)
        @foreach($oldmonth -> collections as $oldcollections)
        <tr id="cuerpo">

            <td>{{ $oldmonth -> detail -> client -> first_name }}</td>
            <td>{{ $oldmonth -> detail -> client -> last_name }}</td>
            <td>{{ $oldmonth -> detail -> client -> mother_last_name }}</td>
            <td>{{ $oldmonth -> detail -> client_eco == 1? 'SI':'NO' }}</td>
            <td>{{ $oldmonth -> detail -> op_client_id }}</td>
            <td>{{departamento($oldmonth -> user -> departamento)}}</td>
            <td>{{$oldmonth -> user -> agency -> name}}</td>
            <td>{{$oldmonth -> user -> full_name}}</td>
            <td>{{$oldcollections -> transaction_amount}}</td>

        </tr>
        @endforeach
    @endforeach
</table>
</html>