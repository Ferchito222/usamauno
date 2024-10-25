@if(isset($retailerProduct) && $step > 0 && in_array($retailerProduct->companyProduct->product->code,['tj','am','ce']))
    <div class="panel-heading divhr">
        <div class="steps-basic2 wizard">
            <div class="steps">               
                <ul>
                    @foreach(config('base.steps.' . $retailerProduct->companyProduct->product->code) as $key => $st)
                        <li class="{{ $key === $step ? 'current' : ($key < $step ? 'first done' : 'disabled last') }}">
                            <a href="#">
                                @if($key === $step)
                                    <span class="current-info audible">current step: </span>
                                @endif
                                <span class="number">{{ $key }}</span> {{ $st }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                title="Detalle de producto" data-placement="right" data-toggle="modal"
                data-target="#modal_theme_primary">
            <i class="icon-question7"></i> Producto
        </button>
    </div>
@else
    @if(isset($product))
        <ul>
            @foreach(config('base.steps.' . $product) as $key => $st)
                @if($key <= $length)
                    <li class="{{ $key === $step ? 'current' : ($key < $step ? 'first done' : 'disabled last') }}">
                        <a href="#">
                            @if($key === $step)
                                <span class="current-info audible">current step: </span>
                            @endif
                            <span class="number">{{ $key }}</span> {{ $st }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
@endif
