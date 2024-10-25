@extends('adm.layout')

@section('content')
    <input type="hidden" id="roc-data" value="{{ route('adm::operation.cancel.data') }}">
    <input type="hidden" id="roc-store" value="{{ route('adm::operation.cancel.store', [ 'rp_id' => ':rp_id', 'header_id' => ':header_id' ]) }}">
    <input type="hidden" id="r-c" value="{{ route('certificate.show', [ 'code' => 'de', 'rp_id' => ':rp_id', 'type' => 'certificate', 'header_id' => ':header_id', 'format' => 'pdf' ]) }}">

    <div id="adm-flux" data-cancel></div>
@endsection