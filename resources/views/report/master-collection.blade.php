@extends('body')
@var $step=0
@section('body')
    <div class="tabbable">
        @include('report.partials.master-tabs')

        <div class="tab-content">
            {!! Form::open(['url' => request()->fullUrl(),
                'method' => 'get',
                'class'  => 'form-horizontal'
            ]) !!}
            <div class="col-xs-12 col-md-12 display-block">
                @include('report.partials.inputs-search-filter-coll')
            </div>
            {!! Form::close() !!}

            <div class="clearfix"></div>
            <hr>

            @if((isset($collections) && $collections->count() > 0))

                @include('report.rows.rows-coll-' . $code)
                <br>
                {!! $collections->appends(request()->all())->render() !!}
                <br>

                <a href="{{ route('report.master.collection', [
                    'code'   => $code,
                    'rp_id'  => $rp_id,
                    'type'   => request()->route()->getParameter('type'),
                    'format' => 'xls',
                    request()->getQueryString(),
                ]) }}" class="btn btn-success">
                    Exportar a Excel
                </a>

            @endif
        </div>
    </div>
@endsection