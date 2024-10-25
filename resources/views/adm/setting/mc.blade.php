@extends('adm.layout')

@section('content')
    <input type="hidden" id="r-mc-list" value="{{ route('adm::mc.data.list') }}">
    <input type="hidden" id="r-mc-questionnaires-edit" value="{{ route('adm::mc.questionnaires.edit', [ 'id' => ':id' ]) }}">
    <input type="hidden" id="r-mc-questionnaires-update" value="{{ route('adm::mc.questionnaires.update', [ 'id' => ':id' ]) }}">

    <mc></mc>
@endsection