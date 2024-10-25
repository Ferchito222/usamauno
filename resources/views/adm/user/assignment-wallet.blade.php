@extends('adm.layout')

@section('content')
    <input type="hidden" id="roa-wallet" value="{{ route('adm::operation.assignment.wallet.store') }}">
    <input type="hidden" id="roa-wallet-users" value="{{ route('adm::operation.assignment.wallet.users') }}">

    <div id="adm-flux" data-assignment-wallet></div>
@endsection