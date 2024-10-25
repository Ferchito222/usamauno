@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'ema'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '4', 'product' => 'ema'])
                @include('partials.messages')
                
                {!! Form::open(['route' => ['ema.client.i.update',
                    'rp_id'     => $rp_id,
                    'header_id' => $header_id,
                    'client_id' => $client_id,
                    isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                    ], 'method' => 'put', 'class' => 'form-horizontal'
                ]) !!}
                <div class="panel-body ">
                    @include('client.ema.partials.inputs-quote', ['product' => 'ema','form'=>'edit'])

                    <div class="text-right">
                        @if(request()->has('coverage'))
                            <input type="hidden" name="coverage" value="{{ request()->get('coverage') }}">

                            <a href="{{ route('ema.coverage.edit', [
                                'rp_id'     => $rp_id,
                                'de_id'     => request()->get('coverage'),
                                'header_id' => $header_id,
                            ]) }}" class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                        @else
                            <a href="{{ route('ema.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                            ]) }}" class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                        @endif

                        {!! Form::button('Actualizar <i class="icon-floppy-disk position-right"></i>', [
                            'type' => 'submit',
                            'class' => 'btn btn-primary'])
                        !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection