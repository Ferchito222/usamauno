@if($type_form === 1)
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
            <div class="form-group has-success">
                <label class="control-label col-lg-4 text-semibold" style="text-align: right;">Busqueda de
                    datos:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-search4"></i></span>
                        {!! Form::text('s_dni', old('s_dni'), [
                        'class'        => 'form-control',
                        'placeholder'  => $placeholder,
                        'autocomplete' => 'off'
                        ]) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endif
@if($type_form === 2)
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
            <div class="form-group has-success">
                <label class="control-label col-lg-3 text-semibold" style="text-align: right;">Busqueda de
                    datos:</label>
                <div class="col-lg-4">

                                    {!! Form::text('s_dni', old('s_dni'), [
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Documento de identidad',
                                        'autocomplete' => 'off',
                                        'style'        => 'width: 180px;'
                                    ]) !!}
                                
                    </div>
                    <div class="col-lg-3">
                                    {!! SelectField::input('s_extension', $data['cities']['CI']->toArray(), [
                                        'class' => 'form-control',
                                        'style' => 'width: 150px; font-size: 90%;'
                                    ], old('s_extension')) !!}
                                
                </div>
                <div class="col-xs-12 col-md-2">
                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endif
@if($type_form === 3)
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
            <div class="form-group has-success">
                <label class="control-label col-lg-4 text-semibold" style="text-align: right;">Busqueda de
                    datos:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-search4"></i></span>
                        {!! Form::text('s_dni', old('s_dni'), [
                            'class'        => 'form-control',
                            'placeholder'  => 'Ingrese Id de Cliente',
                            'autocomplete' => 'off'
                        ]) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endif
