@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('content')
    @if(auth()->user()->profile->first()->slug === 'COP')
        <meta http-equiv="refresh" content="300">
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="wrapper no-pad">
                    @if ($user->profile->first()->slug === 'SEP' || $user->profile->first()->slug === 'COP')
                        @include('de.facultative.inbox')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('status'))
        <script>
            $(function () {
                messageAction('succes', "{{ session('status') }}");
            });
        </script>
    @endif
@endsection