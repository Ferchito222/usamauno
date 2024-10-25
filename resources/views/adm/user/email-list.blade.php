@extends('adm.layout')

@section('content')
    <input type="hidden" id="re-list" value="{{ route('adm::email.data.list') }}">
    <input type="hidden" id="re-edit" value="{{ route('adm::email.update') }}">
    <input type="hidden" id="re-turnoff" value="{{ route('adm::email.turnoff') }}">
    <input type="hidden" id="re-delete" value="{{ route('adm::email.delete') }}">

    <div id="adm-flux" data-email></div>
@endsection