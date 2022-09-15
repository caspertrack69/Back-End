@extends('layouts.admin.app')

@section('title', translate('App settings'))

@push('css_or_js')

    <style>
        .theme_image {
            width: 225px;
            height: 450px;
            border:2px solid #fff;
            /*background: url(img/tiger.png) no-repeat;*/
            box-shadow: 10px 10px 5px #ccc;
            -moz-box-shadow: 10px 10px 5px #ccc;
            -webkit-box-shadow: 10px 10px 5px #ccc;
            -khtml-box-shadow: 10px 10px 5px #ccc;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{translate('App settings')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" style="padding-bottom: 20px">
            <div class="w-100 ">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <div class="text-center">
                            <h3 class="text-primary">{{translate('Select Theme for User App')}}</h3>
                        </div>
                        <div class="mt-4">
                            @php($config=\App\CentralLogics\Helpers::get_business_settings('app_theme'))
                            <div class="row">
                                <div class="col-12 col-md-6 text-center">
                                    <label class="toggle-switch d-flex align-items-center mb-3" for="app_theme_1">
                                        <input type="checkbox" name="welcome_status"
                                               class="toggle-switch-input"
                                               id="app_theme_1" {{isset($config) && $config==1?'checked':''}}
                                               onclick="location.href='{{route('admin.business-settings.app_setting_update', ['theme' => 1])}}'">

                                        <span class="toggle-switch-label p-1">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>

                                        <h5 class="ml-2 mt-2">{{translate('Theme 1')}} </h5>
                                    </label>

                                    <div class="m-4">
                                        <img class="theme_image" src="{{asset('public/assets/admin/img/theme/theme_1.png')}}"/>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 text-center">
                                    <label class="toggle-switch d-flex align-items-center mb-3" for="app_theme_3">
                                        <input type="checkbox" name="welcome_status"
                                               class="toggle-switch-input"
                                               id="app_theme_3" {{isset($config) && $config==3?'checked':''}}
                                               onclick="location.href='{{route('admin.business-settings.app_setting_update', ['theme' => 3])}}'">

                                        <span class="toggle-switch-label p-1">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>

                                        <h5 class="ml-2 mt-2">{{translate('Theme 2')}} </h5>
                                    </label>

                                    <div class="m-4">
                                        <img class="theme_image" src="{{asset('public/assets/admin/img/theme/theme_2.png')}}"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
