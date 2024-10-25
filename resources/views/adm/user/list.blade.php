@extends('adm.layout')

@section('content')
    <input type="hidden" id="ru-list" value="{{ route('adm::user.data.list') }}">
    <input type="hidden" id="ru-activate" value="{{ route('adm::user.activate') }}">
    <input type="hidden" id="ru-create" value="{{ route('adm::user.store') }}">
    <input type="hidden" id="ru-edit" value="{{ route('adm::user.update') }}">
    <input type="hidden" id="ru-password-update" value="{{ route('adm::user.password.update') }}">
    <input type="hidden" id="ru-password-turnoff" value="{{ route('adm::user.password.turnoff') }}">
    <input type="hidden" id="ru-import" value="{{ route('adm::user.import.start') }}">
    <input type="hidden" id="ru-export" value="{{ route('adm::user.export') }}">

    <div id="adm-flux" data-user></div>

@endsection