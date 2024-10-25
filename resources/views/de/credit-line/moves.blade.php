<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Líneas de Crédito - Movimientos</title>

  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
</head>
<body>
  <div class="container">
    @if (count($data) > 0)
      <h4>Movimientos</h4>
      
      @foreach ($data as $item)
        <table class="table table-striped table-bordered table-condensed table-hover table-responsive">
          <tr class="success">
            <td><strong>Nro. de Operación:</strong></td>
            <td>{{ $item['nroOperacion'] }}</td>
          </tr>
          <tr class="info">
            <td><strong>Tasa Final:</strong></td>
            <td>{{ $item['tasaFinal'] }}</td>
          </tr>
          <tr>
            <td><strong>Extra Prima:</strong></td>
            <td>{{ $item['tasaExtraPrima'] }}</td>
          </tr>
          <tr>
            <td><strong>Tipo:</strong></td>
            <td>{{ $item['tipo'] }}</td>
          </tr>
          <tr>
            <td><strong>C.I.:</strong></td>
            <td>{{ $item['ci'] }}</td>
          </tr>
          <tr>
            <td><strong>Aprobado:</strong></td>
            <td>{{ $item['aprobado'] ? 'SI' : 'NO' }}</td>
          </tr>
          <tr>
            <td><strong>Licitado:</strong></td>
            <td>{{ $item['eslicitado'] ? 'SI' : 'NO' }}</td>
          </tr>
          <tr>
            <td><strong>Seguro de Cesantia:</strong></td>
            <td>{{ $item['seguroCesantia'] ? 'SI' : 'NO' }}</td>
          </tr>
          {{--  <tr>
            <td></td>
            <td>{{ isset($item['response']) ? $item['response'] : '' }}</td>
          </tr>  --}}
        </table>
        <hr>
      @endforeach
    @else
      <h4>No existen Movimientos</h4>
    @endif
  </div>
</body>
</html>