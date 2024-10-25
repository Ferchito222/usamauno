@extends('adm.layout')

@section('content')
    <input type="hidden" id="ru-import" value="{{ route('adm::user.import.complete') }}">
    <input type="hidden" id="ru-list" value="{{ route('adm::user.list') }}">

    <div id="adm-flux" data-user-import></div>
@endsection