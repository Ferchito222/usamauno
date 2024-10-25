@extends('adm.layout')

@section('content')
    <input type="hidden" id="roAc-lists" value="{{ route('adm::operation.attachedCertificate.lists') }}">
    <input type="hidden" id="roAc-delete" value="{{ route('adm::operation.attachedCertificate.delete', [ 'id' => ':id' ]) }}">
    <input type="hidden" id="roAc-attached" value="{{ route('de.attach.file', [ 'file' => ':file' ]) }}">

    <div id="adm-flux" data-attached-certificate></div>
@endsection