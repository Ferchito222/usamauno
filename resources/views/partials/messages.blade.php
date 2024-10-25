<!--Mensajes de error o éxito-->
@if(session('error_header'))
    <div class="clearfix"><br /><br /></div>
    <div class="alert alert-danger alert-styled-right">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        <span class="text-semibold">{{ session('error_header') }}</span>.
    </div>
@endif

@if(session('error_client'))
    <div class="clearfix"><br /><br /></div>
    <div class="alert bg-danger alert-styled-right">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        <span class="text-semibold">{{ session('error_client') }}</span>.
    </div>
@endif

@if(session('error_ws'))
    <div class="clearfix"><br /><br /></div>
    <div class="alert bg-danger alert-styled-right">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        <span class="text-semibold">{{ session('error_ws') }}</span>.
    </div>
@endif

@if(session('err_client'))
    <div class="alert alert-warning alert-styled-right">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        <span class="text-semibold">{!! session('err_client') !!}</span>.
    </div>
@endif

@if(session('error_client'))
    <script>
        $(function () {
            messageAction('info', "{{ session('error_client') }}");
        });
    </script>
@endif

@if(session('error_detail'))
    <script>
        $(function () {
            messageAction('info', "{{ session('error_detail') }}");
        });
    </script>
@endif

@if(session('success_header'))
    <script>
        $(function () {
            messageAction('succes', "{{ session('success_header') }}");
        });
    </script>
@endif

@if(session('error_header'))
    <script>
        $(function () {
            messageAction('error', "{{ session('error_header') }}");
        });
    </script>
@endif

@if(session('success_client'))
    <script>
        $(function () {
            messageAction('succes', "{{ session('success_client') }}");
        });
    </script>
@endif

@if(session('error_question'))
    <div class="alert bg-danger alert-styled-right">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        <span class="text-semibold">{{ session('error_question') }}</span>.
    </div>
@endif

@if(session('success_question'))
    <script>

        $(function () {
            messageAction('succes', "{{ session('success_question') }}");
        });
    </script>
@endif
