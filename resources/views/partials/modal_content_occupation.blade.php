<!-- Scrollable table -->
<div class="panel panel-flat">
    @var $i=1
    <div class="table-responsive pre-scrollable">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Ocupación</th>
            </tr>
            </thead>
            <tbody>
            @foreach($query_occupation as $data_occupation)
                <tr>
                    <td>{{$i}}</td>
                    <td style="text-align: left;">{{$data_occupation->occupation}}</td>
                </tr>
                @var $i = $i+1
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /scrollable table -->