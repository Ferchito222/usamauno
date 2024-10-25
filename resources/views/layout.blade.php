<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="cache-control" content="max-age=0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta http-equiv="pragma" content="no-cache">

    <title>Abrenet</title>

    <!-- Global stylesheets -->
    {!! Html::style('assets/css/google.css') !!}
    {!! Html::style('assets/css/icons/icomoon/styles.css') !!}
    {!! Html::style('assets/css/icons/fontawesome/styles.min.css') !!}
    {!! Html::style('assets/css/bootstrap.min.css') !!}
    {!! Html::style('assets/css/core.min.css') !!}
    {!! Html::style('assets/css/components.min.css') !!}
    {!! Html::style('assets/css/colors.min.css') !!}
    {!! Html::style('css/style.css') !!}
    {!! Html::style('css/style_inbox.css') !!}
    {!! Html::style('css/animate.min.css') !!}
    {!! Html::style('css/jquery.loading.css') !!}
            <!-- /global stylesheets -->
    <!-- Core JS files -->
    {!! Html::script('assets/js/core/libraries/jquery.min.js') !!}
    {!! Html::script('assets/js/plugins/loaders/pace.min.js') !!}
    {!! Html::script('assets/js/core/libraries/bootstrap.min.js') !!}
    {!! Html::script('assets/js/plugins/loaders/blockui.min.js') !!}
    {!! Html::script('assets/js/plugins/ui/nicescroll.min.js') !!}
    {!! Html::script('assets/js/plugins/ui/drilldown.js') !!}
            <!-- /core JS files -->
    <!-- Theme JS files -->
    {!! Html::script('assets/js/plugins/forms/validation/validate.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/selects/bootstrap_multiselect.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/uniform.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/inputs/touchspin.min.js') !!}

    @if(! isset($app_v))
        {!! Html::script('assets/js/pages/form_input_groups.js') !!}
    @endif

    {!! Html::script('assets/js/core/libraries/jquery_ui/interactions.min.js') !!}

    @if(! isset($app_v))
        {!! Html::script('assets/js/pages/form_select2.js') !!}
        {!! Html::script('assets/js/plugins/forms/selects/bootstrap_select.min.js') !!}
        {!! Html::script('assets/js/pages/form_bootstrap_select.js') !!}
        {!! Html::script('assets/js/pages/form_validation.js') !!}
        {!! Html::script('js/functions.js') !!}
        <!-- /theme JS files -->

        <!--picker date-->
        {!! Html::script('assets/js/core/libraries/jquery_ui/datepicker.min.js') !!}
        {!! Html::script('assets/js/plugins/ui/moment/moment.min.js') !!}
        {!! Html::script('assets/js/plugins/pickers/daterangepicker.js') !!}
        {!! Html::script('assets/js/plugins/pickers/anytime.min.js') !!}
        {!! Html::script('assets/js/plugins/pickers/pickadate/picker.js') !!}
        {!! Html::script('assets/js/plugins/pickers/pickadate/picker.date.js') !!}
        {!! Html::script('assets/js/plugins/pickers/pickadate/picker.time.js') !!}
        {!! Html::script('assets/js/plugins/pickers/pickadate/legacy.js') !!}
        {!! Html::script('assets/js/pages/picker_date.js') !!}
        <!--fin picker date-->

        <!--datatable-->
        {!! Html::script('assets/js/plugins/tables/datatables/datatables.min.js') !!}
        {!! Html::script('assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js') !!}
        {!! Html::script('assets/js/plugins/forms/selects/select2.min.js') !!}
        {!! Html::script('assets/js/core/app.js') !!}
        {!! Html::script('assets/js/pages/datatables_extension_fixed_columns.js') !!}
        {!! Html::script('assets/js/pages/datatables_basic.js') !!}
        <!--fin datatable-->

        <!--notificacion-->
        {!! Html::script('assets/js/plugins/notifications/pnotify.min.js') !!}
        {!! Html::script('assets/js/plugins/notifications/noty.min.js') !!}
        {!! Html::script('assets/js/plugins/notifications/jgrowl.min.js') !!}
        {!! Html::script('assets/js/plugins/notifications/bootbox.min.js') !!}
        {!! Html::script('assets/js/pages/components_notifications_other.js') !!}
        <!--fin notificacion-->
    @endif

    {!! Html::script('js/jquery.loading.min.js') !!}
    {!! Html::script('js/jquery.PrintArea.js') !!}

    <script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
</head>
<body class="layout-boxed" ng-app="sibas" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
<div>
    <!-- Main navbar -->
    @yield('header')
    <!-- /main navbar -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="cabecera">
                </div>
            </div>
        </div>
    <!-- Second navbar -->
    @yield('menu-main')
    <!-- /second navbar -->
    <!-- Page header -->
    @yield('menu-header')

    @yield('content-flux')
    <!-- /page header -->
        <!-- Page container -->
        <div class="page-container @yield('class-container')">
            <!-- Page content -->
            <div class="page-content">
                <!-- Main content -->
                <div class="content-wrapper">
                    <!-- Grid -->
                    @yield('content')

                </div>
            <!-- /grid -->
            </div>
            <!-- /main content -->
            <!-- Footer -->
            <div class="footer text-muted">
                &copy; {{date('Y')}}. <a href="http://abrenet.com" target="_blank">Powered by Abrenet</a> © {{date('Y')}}
            </div>
            <!-- /footer -->
        </div>
        <!-- modal information product -->
    @if(! isset($app_v))
        @include('partials.information_product')
    @endif
    <!-- /modal -->

    @include('partials.popup')
</div>

@if(! isset($app_v))
    {!! Html::script('js/app.js') !!}
@else
    <div id="Loader-wrapper">
        <div class="Sk-cubeGrid">
            <div class="Sk-cube Sk-cube1"></div>
            <div class="Sk-cube Sk-cube2"></div>
            <div class="Sk-cube Sk-cube3"></div>
            <div class="Sk-cube Sk-cube4"></div>
            <div class="Sk-cube Sk-cube5"></div>
            <div class="Sk-cube Sk-cube6"></div>
            <div class="Sk-cube Sk-cube7"></div>
            <div class="Sk-cube Sk-cube8"></div>
            <div class="Sk-cube Sk-cube9"></div>
        </div>
        Por favor espere...
    </div>

    {!! Html::script('assets/js/app.js') !!}

    {{--<script src="{{ asset('assets/app-adm.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-4/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-4/js/bootstrap.min.js') }}"></script>--}}
@endif

</body>
</html>
