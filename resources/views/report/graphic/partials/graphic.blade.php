@if($format === 'pdf')
    <meta charset="utf-8">    
@endif

@var $user_profile = $user->profile->first()->slug;
@var $user_permissions = $user->permissions->pluck('slug')->toArray();

{!! Html::script('assets/js/plugins/visualization/echarts/echarts.js') !!}
{!! Html::script('assets/js/pages/components_loaders.js') !!}

@var $meta_venta    = $retailerProduct->goals->goal*$headers['diffMonth']
@var $description   = (dateYear(request()->get('date_begin'))==dateYear(request()->get('date_end')))?dateYear(request()->get('date_begin')):dateYear(request()->get('date_begin')).' a '.dateYear(request()->get('date_end'))

@if($format == 'pdf')
    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
        <tr>
            <td style="width: 30%;">
                @var $company = [];
                @foreach (auth()->user()->retailerUser->retailer->retailerProducts as $retailerP)
                    @if (! in_array($retailerP->companyProduct->company->id, $company, true) && $retailerP->companyProduct->company->active == true)
                        <span style="padding: 0;">
                            {!! Html::image($retailerP->companyProduct->company->image, '', ['height' => '50']) !!}
                        </span>
                    <?php array_push($company, $retailerP->companyProduct->company->id) ?>
                    @endif
                @endforeach
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 30%;"><img src="{{ asset('/images/logosudecs.jpg') }}" height="50"></td>
            <td style="width: 5%;"></td>
            <td style="width: 30%; text-align: right">
                <img src="{{ base_path('public/' . $retailerProduct->retailer->image) }}" height="50">
            </td>
        </tr>
    </table><br />
@endif

<p style="color:#A5A5A5;">Datos generales ({{$description}}) con meta de {{$meta_venta}} ventas por FUNCIONARIO</p>

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }}">
    <br />
    <table border="0" width="100%" style="text-align: left;" class="small_body">
        <tr>
            @foreach (getProducts() as $keyP => $product)
            <td style="width: {{100/count(getProducts())}}%;color:#A5A5A5;" >
                <span class="{{$format!='pdf'?'badge bg-warning-400':'circle_pdf'}}">{{strtolower($keyP)}}</span>: {{$product}}
            </td>   
            @endforeach
        </tr>
    </table>
    <br />
    @include('report.graphic.partials.nacional')
    
    @foreach ($headers['sucursal'] as $Skey => $Svalue)
        @include('report.graphic.partials.sucursal')
        @include('report.graphic.partials.users')
    @endforeach
    @include('report.graphic.partials.max-ventas')
    @if($format !== 'xls')
    <input type="hidden" id="url-upload" value="">
    @endif
</div>
@if($format == 'pdf')
    <style>
        .small_body {
        font-size: 8px;
        }
        .circle_pdf {
        background: #FF7043;
        width: 23px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        text-align: center;
        color: white;
        }
    </style>
@endif