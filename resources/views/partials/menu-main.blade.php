<div class="navbar navbar-default" id="navbar-second">
    <div class="navbar-boxed">
        <ul class="nav navbar-nav no-border visible-xs-block">
            <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i
                            class="icon-menu7"></i></a></li>
        </ul>
        <div class="navbar-collapse collapse" id="navbar-second-toggle">
            @var $current_route    = request()->route()->getName();
            @var $user_profile     = auth()->user()->profile->first()->slug;
            @var $user_permissions = auth()->user()->permissions->pluck('slug')->toArray();

            <ul class="nav navbar-nav">
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="icon-home2 position-left"></i> Inicio
                    </a>
                </li>
                <li class="dropdown">
                    @if($user_profile !== 'GUP')
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Descargar Formularios <span class="caret"></span>
                        </a>
                    @endif
                    <ul class="dropdown-menu width-200">
                        @foreach(auth()->user()->retailerUser->retailer->retailerProducts as $retailerProduct)
                            @var $flag_product = false;

                            @if($retailerProduct->type == 'MP' && $retailerProduct->active)
                                @if($user_profile === 'COP'
                                    && in_array($retailerProduct->companyProduct->ad_company_id, auth()->user()->retailerUser->companies))
                                    @var $flag_product = true;
                                @elseif($user_profile === 'SEP')
                                    @if(auth()->user()->retailerUser->products()->where('ad_products.id', $retailerProduct->companyProduct->product->id)->count() === 1)
                                        @var $flag_product = true;
                                    @endif
                                @endif

                                @if($flag_product)
                                    <li class="dropdown-header">{{ $retailerProduct->companyProduct->product->name }}</li>
                                    @foreach ($retailerProduct->forms as $form)
                                        <li>
                                            <a href="{{ asset($form->file) }}" target="_blank">
                                                <i class="icon-align-center-horizontal"></i> {{ $form->title }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </li>

                @if($user_profile === 'SEP' || $user_profile === 'GUP')
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Productos <span class="caret"></span></a>
                        <ul class="dropdown-menu width-200">
                            @foreach(auth()->user()->retailerUser->retailer->retailerProducts as $retailerProduct)
                                @var $flag_product = false;

                                @if(($retailerProduct->type == 'MP' || $retailerProduct->type == 'SP') && $retailerProduct->active)
                                    @if(auth()->user()->retailerUser->products()->where('ad_products.id', $retailerProduct->companyProduct->product->id)->count() === 1)
                                        @var $flag_product = true;
                                    @endif

                                    @if($flag_product)
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-list-unordered"></i> {{ $retailerProduct->companyProduct->product->name }}
                                            </a>
                                            <ul class="dropdown-menu">
                                                @if(request()->route()->hasParameter('client'))
                                                    @var $client = encode($client->id);
                                                @else
                                                    @var $client = ''
                                                @endif

                                                @if(request()->route()->hasParameter('detail_id'))
                                                    @var $detail_id = request()->get('detail_id')
                                                @else
                                                    @var $detail_id = ''
                                                @endif

                                                @if(request()->route()->hasParameter('header_id'))
                                                    @var $header_id = $header_id
                                                @else
                                                    @var $header_id = ''
                                                @endif

                                                @if(request()->route()->hasParameter('header'))
                                                    @var $header = encode($header->id);
                                                @else
                                                    @var $header = ''
                                                @endif

                                                @if(request()->route()->hasParameter('detail'))
                                                    @var $detail = encode($detail->id);
                                                @else
                                                    @var $detail = ''
                                                @endif

                                                @if(request()->route()->hasParameter('sp_id'))
                                                    @var $sp_id = $sp_id
                                                @else
                                                    @var $sp_id = ''
                                                @endif

                                                <li class="{{
                                                    Request::is('de/'.encode($retailerProduct->id).'/create') ? 'active' :
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/list') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'client') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'client'.$client.'') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'client'.$detail_id.'/question/create') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'client'.$detail_id.'') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/result') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/edit') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/beneficiary/create/'.$detail.'') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'client'.$detail_id.'/question/edit') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/issuance') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/vi/'.$sp_id.'') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header_id.'/vi/'.$sp_id.'/create') ? 'active':
                                                    Request::is('de/'.encode($retailerProduct->id).'/'.$header.'/balance/edit/'.$detail.'') ? 'active':
                                                    ''
                                                   }}

                                                @if($retailerProduct->companyProduct->product->code === 'au')
                                                {{ $current_route === 'au.create' ? 'active' : '' }}
                                                {{ $current_route === 'au.vh.lists' ? 'active' : '' }}
                                                {{ $current_route === 'au.result' ? 'active' : '' }}
                                                {{ $current_route === 'au.edit' ? 'active' : '' }}
                                                @endif
                                                @if($retailerProduct->companyProduct->product->code === 'mr')
                                                {{ $current_route === 'td.create' ? 'active' : '' }}
                                                {{ $current_route === 'td.vh.lists' ? 'active' : '' }}
                                                {{ $current_route === 'td.result' ? 'active' : '' }}
                                                {{ $current_route === 'td.edit' ? 'active' : '' }}
                                                @endif
                                                @if($retailerProduct->companyProduct->product->code === 'elp')
                                                {{ $current_route === 'elp.create' ? 'active' : '' }}
                                                {{ $current_route === 'elp.lists' ? 'active' : '' }}
                                                {{ $current_route === 'elp.edit' ? 'active' : '' }}
                                                {{ $current_route === 'elp.result' ? 'active' : '' }}
                                                {{ $current_route === 'elp.edit.issuance' ? 'active' : '' }}
                                                {{ $current_route === 'elp.client.edit' ? 'active' : '' }}
                                                {{ $current_route === 'elp.issuance' ? 'active' : '' }}
                                                @endif
                                                        ">
                                                    <a href="{{ route($retailerProduct->companyProduct->product->code . '.create', ['rp_id' => encode($retailerProduct->id)]) }}">Cotizar</a>
                                                </li>
                                                @if ($user_profile !== 'GUP')
                                                    @if(in_array('CADE', $user_permissions))
                                                        <li class="{{ $current_route === $retailerProduct->companyProduct->product->code . '.cancel.lists' ? 'active' : '' }}">
                                                            <a href="{{ route($retailerProduct->companyProduct->product->code . '.cancel.lists', ['rp_id' => encode($retailerProduct->id)]) }}">
                                                                Anular Certificados
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="{{ $current_route === $retailerProduct->companyProduct->product->code. '.pre.approved.lists' ? 'active' : '' }}">
                                                        <a href="{{ route($retailerProduct->companyProduct->product->code . '.pre.approved.lists', ['rp_id' => encode($retailerProduct->id)]) }}">Solicitudes
                                                            Preaprobadas</a>
                                                    </li>
                                                    <li class="{{ $current_route === $retailerProduct->companyProduct->product->code . '.issue.lists' && ! request()->route()->hasParameter('guest') ? 'active' : '' }}">
                                                        <a href="{{ route($retailerProduct->companyProduct->product->code . '.issue.lists', ['rp_id' => encode($retailerProduct->id)]) }}">Emitir
                                                            Solicitudes</a>
                                                    </li>
                                                    @if($retailerProduct->companyProduct->product->code === 'de_comentado')
                                                        <li class="{{ $current_route === $retailerProduct->companyProduct->product->code . '.issue.lists' && request()->route()->hasParameter('guest') ? 'active' : '' }}">
                                                            <a href="{{ route($retailerProduct->companyProduct->product->code . '.issue.lists', ['rp_id' => encode($retailerProduct->id), 'guest' => 'guest']) }}">
                                                                Emitir solicitudes externas
                                                                @if(auth()->user()->total_external_request > 0)
                                                                    <div class="external-request-box">
                                                                        {{ auth()->user()->total_external_request }}
                                                                    </div>
                                                                @endif
                                                            </a>
                                                        </li>

                                                        @if(in_array('LCI', $user_permissions))
                                                            <li class="{{ $current_route === 'de.creditLine.issuance' ? 'active' : '' }}">
                                                                <a href="{{ route('de.creditLine.issuance', [ 'rp_id' => encode($retailerProduct->id), 'cl' => '' ]) }}">
                                                                    Emitir operaciones bajo Línea
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                @if(in_array($user_profile, [ 'SEP', 'COP', 'REP', 'OPP']) || count(array_intersect(['RN', 'RR', 'RU'], $user_permissions)) > 0)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes <span class="caret"></span></a>
                        <ul class="dropdown-menu width-200">
                            @foreach(auth()->user()->retailerUser->retailer->retailerProducts as $retailerProduct)
                                @var $flag_product = false;

                                @if(($retailerProduct->type == 'MP' || $retailerProduct->type == 'SP') && $retailerProduct->active)
                                    @if($user_profile === 'COP'
                                        && in_array($retailerProduct->companyProduct->ad_company_id, auth()->user()->retailerUser->companies))
                                        @var $flag_product = true;
                                    @elseif(in_array($user_profile, [ 'SEP', 'REP', 'OPP']))
                                        @if(auth()->user()->retailerUser->products()->where('ad_products.id', $retailerProduct->companyProduct->product->id)->count() === 1)
                                            @var $flag_product = true;
                                        @endif
                                    @endif

                                    @if($flag_product)
                                        <li class="">
                                            @var $route_report = '';
                                            @var $code         = $retailerProduct->companyProduct->product->code;

                                            @if($code == 'de' || $code == 'elp' || $code == 'eb' || $code == 'ema' || $code == 'rma' || $code == 'td' || $code == 'au' || $code == 'tj' || $code == 'vd')
                                                @var $route_report = 'master';
                                            @endif

                                            @var $route = route('report.' . $route_report, [ 'id_comp' => encode($retailerProduct->id) ])
                                            @if($code == 'de' || $code == 'elp' || $code == 'eb' || $code == 'ema' || $code == 'rma' || $code == 'td' || $code == 'au' || $code == 'tj' || $code == 'vd')
                                                @var $route = route('report.' . $route_report, [ 'code' => $code, 'rp_id' => encode($retailerProduct->id), 'type' => 'GEN' ])
                                            @endif
                                            <a href="{{ $route }}">
                                                <i class="icon-list-unordered"></i> {{ $retailerProduct->companyProduct->product->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach

                            @if(in_array('RGP', $user_permissions))
                                <li class="{{ $current_route === 'report.graphic' ? 'active' : '' }}">
                                    <a href="{{ route('report.graphic') }}">
                                        <i class="icon-list-unordered"></i> Gráficos
                                    </a>
                                </li>
                            @endif
                            @foreach(auth()->user()->permissions as $permission)
                                @if($permission->slug === 'RPG')
                                    <li class="{{ request()->route()->getName() === 'graphic.report' ? 'active' : '' }}">
                                        <a href="{{ route('graphic.report',['format'=>'html']) }}">
                                            <i class="icon-graph"></i>  Reporte Gráfico
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                @foreach(auth()->user()->permissions as $permission)
                    @if($permission->slug === 'EDPO')
                        <li class="{{ request()->route()->getName() === 'dp.search' ? 'active' : '' }}">
                            <a href="{{ route('dp.search') }}">{{ $permission->name }}</a>
                        </li>
                    @endif
                @endforeach
                
                @if(count(array_intersect([ 'LCR' ], $user_permissions)) > 0)
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Operaciones <span class="caret"></span></a>
                        <ul class="dropdown-menu width-200">
                            @if(in_array('LCR', $user_permissions))
                                <li class="{{ $current_route === 'de.creditLine.create' ? 'active' : '' }}">
                                    <a href="{{ route('de.creditLine.create') }}">Línea de Crédito</a>
                                </li>
                            @endif
                        </ul>
                    </li>-->
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <span style="text-align: center;"><i class="icon-user"></i>
                            @if(request()->session()->has('guest'))
                                {{ request()->session()->get('guest')->first_name }} {{ request()->session()->get('guest')->last_name }}
                                /
                                {{ request()->session()->get('guest')->email }}
                            @else
                                {{ auth()->user()->full_name }}
                            @endif
                        </span>
                        <i class="caret"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        {{--<li><a href="{{ route('user.options') }}"><i class="icon-user-tie"></i>Opciones de Usuario</a></li>--}}
                        {{--<li><a href="#"><i class="icon-lock5"></i> Cambiar clave</a></li>--}}
                        <li class="divider"></li>
                        {{--<li><a href="#"><i class="icon-phone"></i> Telefono Agencia</a></li>--}}
                        <li><a href="{{ route('auth.logout') }}"><i class="icon-switch2"></i> Salir</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
