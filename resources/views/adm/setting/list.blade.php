@extends('adm.layout')

@section('content')
    <input type="hidden" id="route-setting-list" value="{{ route('adm::setting.data.list') }}">
    <input type="hidden" id="route-setting-update" value="{{ route('adm::setting.update') }}">

    <setting-list></setting-list>

@endsection