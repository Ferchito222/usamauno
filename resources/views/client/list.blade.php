@extends('body')
@var $step=0
@section('body')
    <div style="width: 100%; overflow: scroll;">
        @if(isset($clientWs[0]))
            <table class="table-bordered table-striped table-hover" width="100%" style="font-size: 75%;">
                <thead>
                <tr style="font-weight: bold;">
                    @foreach(array_keys($clientWs[0]) as $key => $value)
                        <th>{{ $value }}</th>    
                    @endforeach
                </tr>
                </thead>
                <tbody>
                

                @foreach($clientWs as $key => $value)
                    <tr>
                        @foreach(array_keys($value) as $key1 => $value1)
                            <td align="right">{{$value[$value1]}}</td>
                        @endforeach    
                    </tr>
                @endforeach

                </tbody>
            </table>
        @else
            <pre>
                {{ var_dump($clientWs) }}
            </pre>
        @endif
    </div>
@endsection