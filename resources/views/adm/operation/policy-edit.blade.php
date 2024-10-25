@extends('adm.layout')

@section('content')
    <input type="hidden" id="r-ope-s" value="{{ route('adm::operation.policyEdit.search') }}">
    <input type="hidden" id="r-ope-e" value="{{ route('adm::operation.policyEdit.edit', ['code' => ':code']) }}">
    <input type="hidden" id="r-ope-u" value="{{ route('adm::operation.policyEdit.update', ['code' => ':code', 'id' => ':id']) }}">

    <input type="hidden" id="r-c" value="{{ route('certificate.show', [ 'code' => ':code', 'rp_id' => ':rp_id', 'type' => 'certificate', 'header_id' => ':header_id', 'format' => 'pdf' ]) }}">

    <div id="adm-flux" data-policy-edit></div>

@endsection