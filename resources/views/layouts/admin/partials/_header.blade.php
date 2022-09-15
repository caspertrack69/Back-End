<div id="headerMain" class="d-none">
    <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
{{--                @php($restaurant_logo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value)--}}
{{--                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="">--}}
{{--                    <img class="navbar-brand-logo" style="max-height: 48px; border-radius: 8px"--}}
{{--                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"--}}
{{--                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}" alt="Logo">--}}
{{--                    <img class="navbar-brand-logo-mini" style="max-height: 48px; border-radius: 8px"--}}
{{--                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"--}}
{{--                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}" alt="Logo">--}}
{{--                </a>--}}
                <!-- End Logo -->
            </div>

            <div class="navbar-nav-wrap-content-left">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">

                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="hs-unfold">
                            <div style="background:white;padding: 9px;border-radius: 5px;">
                                @php( $local = session()->has('local')?session('local'):'en')
                                @php($lang = \App\CentralLogics\Helpers::get_business_settings('language')??null)
                                <div class="topbar-text dropdown disable-autohide mr-3 text-capitalize">
                                    @if(isset($lang))
                                        <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown" style="color: black!important;">
                                            @foreach($lang as $data)
                                                @if($data['code']==$local)
                                                    {{$data['name']}}
                                                @endif
                                            @endforeach
                                        </a>
                                        <ul class="dropdown-menu">
                                            @foreach($lang as $key =>$data)
                                                @if($data['status']==1)
                                                    <li>
                                                        <a class="dropdown-item pb-1"
                                                           href="{{route('admin.lang',[$data['code']])}}">
                                                            <span style="text-transform: capitalize">{{\App\CentralLogics\Helpers::get_language_name($data['code'])}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

                    {{--<li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('admin.message.list')}}">
                                <i class="tio-messages-outlined"></i>
                                @php($message=\App\Models\Conversation::where('checked',0)->count())
                                @if($message!=0)
                                    <span class="btn-status btn-sm-status btn-status-danger"></span>
                                @endif
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>--}}

                    <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
{{--                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"--}}
{{--                               href="#">--}}
{{--                                <i class="tio-shopping-cart-outlined"></i>--}}
{{--                                <span class="btn-status btn-sm-status btn-status-danger"></span>--}}
{{--                            </a>--}}
                        </div>
                        <!-- End Notification -->
                    </li>


                    <li class="nav-item">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img"
                                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                         src="{{asset('storage/app/public/admin')}}/{{auth('user')->user()->image??''}}"
                                         alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                                 style="width: 16rem;">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img"
                                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/app/public/admin')}}/{{auth('user')->user()->image??''}}"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('user')->user()->f_name??''}}</span>
                                            <span class="card-text">{{auth('user')->user()->email??''}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                    <span class="text-truncate pr-2" title="Settings">{{translate('settings')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: '{{translate('Do you want to logout?')}}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#014F5B',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('admin.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2" title="Sign out">{{translate('sign_out')}}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
