@if(in_array($data['detail']['header']['creditProduct']['slug'], [ 'PMO', 'PLT' ]))
    @var $arrHelp = [1,2,3,4,6,8]
@else
    @var $arrHelp = [2,3,5,7]
@endif
<div class="row">
    <div class="col-md-12">
        @foreach($data['questions'] as $index => $question)
            <div class="form-group">
                <div class="col-xs-12 col-md-10">
                    <label class="radio-inline text-semibold">
                        <strong>{{ $question['order'] }}</strong>.
                        {{ $question['question'] }}
                    </label>
                    @if(in_array($question['order'], $arrHelp))
                        <div class="alert-danger" id="{{ 'id_' . $question['order'] }}" style="display: none;font-size: 10px;">
                            - Diagnostico (Aclaración de Patología/enfermedad/accidentes/otros.)<br />
                            - Fecha del diagnóstico.<br />
                            - Centro médico.<br />
                            - Tratamiento.<br />
                            - Estado actual de la patología.
                        </div>
                    @endif
                </div>
                <div class="col-xs-12 col-md-2">
                    {!! Form::hidden('qs[' . $question['order'] . '][id]', $question['id']) !!}
                    {!! Form::hidden('qs[' . $question['order'] . '][question]', $question['question']) !!}
                    {!! Form::hidden('qs[' . $question['order'] . '][expected]', $question['expected']) !!}
                    {!! Form::hidden('qs[' . $question['order'] . '][type]', $question['type']) !!}
                    {!! Form::hidden('qs[' . $question['order'] . '][response_text]', (int) $question['response_text']) !!}
                    @var $idH = '#id_'.$question['order']
                    <label class="radio-inline radio-right">
                        {!! Form::radio('qs[' . $question['order'] . '][response]', '1', ($action === 'CREATE' ? '' : $question['check_yes']), ['class' => 'styled', 'onclick' => '$("'.$idH.'").show()']) !!}
                        Si
                    </label>
                    <label class="radio-inline radio-right">
                        {!! Form::radio('qs[' . $question['order'] . '][response]', '0', ($action === 'CREATE' ? '' : $question['check_no']), ['class' => 'styled', 'onclick' => '$("'.$idH.'").hide()']) !!}
                        No
                    </label>
                    @if(in_array($question['order'], $arrHelp))
                        <script>
                            $(function() {
                                if($('input:radio[name="qs['+{{$question['order']}}+'][response]"]:checked').val() == true){
                                    $('#id_'+{{$question['order']}}).show();
                                }else{
                                    $('#id_'+{{$question['order']}}).hide();
                                }
                            });
                        </script>
                    @endif
                </div>
            </div>


            @if((in_array($question['type'], [ 'PMO', 'PLT' ]) && $question['response_text']) || $question['type'] == null)
                <div class="form-group" align="center">
                    <input type="text" class="form-control" autocomplete="off" maxlength="2000"
                           name="{{ 'qs[' . $question['order'] . '][response_specification]' }}"
                           style="width: 70%; border: none; border-bottom: 1px dashed #000000;"
                           placeholder="En caso que la respuesta sea afirmativa, favor especificar"
                           value="{{ old('qs.' . $question['order'] . '.response_specification', $question['response_specification']) }}">

                    @if ($errors->first('qs.' . $question['order'] . '.response_specification'))
                        <span class="validation-error-label" for="location">Especificación requerida.</span>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>

@var $mess = '';

@foreach($errors->all() as $error)
    @if(($pos = strpos($error, '.response ')) !== false)
        @var $mess .= substr($error, ($pos - 1), 1) . ', ';
    @endif
@endforeach

@if(! empty($mess))
    <label id="location-error" class="validation-error-label" for="location">
        Las preguntas {{ trim($mess, ', ') }} son obligatorias.
    </label>
@endif

<hr>
<div class="form-group">
    
    {{--@if(! in_array($data['detail']->header->creditProduct->slug, [ 'PMO', 'PLT' ]))--}}
    @if(false)
        {!! Form::textarea('qs_observation', old('desc_occupation', $data['observation']), [
            'size'         => '4x4',
            'class'        => 'form-control',
            'placeholder'  => 'Observación',
            'autocomplete' => 'off',
        ]) !!}

        @if($errors->has('qs_observation'))
            <label id="location-error" class="validation-error-label">
                Describa en detalle el motivo de sus respuestas que puedan indicar complicaciones de salud.
            </label>
        @endif
    @else
        {!! Form::hidden('credit_product', md5(1)) !!}
    @endif

    {!! Form::hidden('qs_number', count($data['questions'])) !!}
</div>