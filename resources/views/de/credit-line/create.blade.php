@extends('layout', [ 'app_v' => true ])

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('content')
    @var $coverages = config('base.credit_line.coverages');

    <input type="hidden" id="rde-cl-store" value="{{ route('de.creditLine.store') }}">
    <input type="hidden" id="rde-cl-number-client" value="{{ route('de.creditLine.numberClient') }}">
    <input type="hidden" id="rde-cl-confirm" value="{{ route('de.creditLine.confirm') }}">

    <input type="hidden" id="cl-coverages" value="{{ json_encode(['' => 'Seleccione...'] + $coverages) }}">
    <input type="hidden" id="cl-policy-types" value="{{ json_encode($policy_types) }}">

    <div id="app-flux" data-credit-line></div>
@endsection