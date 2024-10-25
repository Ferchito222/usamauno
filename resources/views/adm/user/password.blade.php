@extends('adm.layout')

@section('content')
    <input type="hidden" id="route-user-password-update" value="{{ route('adm::user.password.update') }}">

    <user-auth :user="{{ auth()->user() }}"></user-auth>
@endsection