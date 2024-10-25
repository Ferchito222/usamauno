@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i>
                    <span class="text-semibold">Seguro de {{$retailerProduct->companyProduct->product->name}}</span>
                </h4>
                @if($step>0)
                    <ul class="breadcrumb breadcrumb-caret position-right">
                        <li><a href="">Inicio</a></li>
                        <li><a href="">{{$retailerProduct->companyProduct->product->name}}</a></li>
                        <li class="active">Cotizar</li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                @include('partials.steps')    
                @include('partials.messages')
                <div class="panel-body">
                    @yield('body')
                </div>
            </div>
        </div>
@endsection
