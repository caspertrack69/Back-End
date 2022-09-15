@extends('layouts.admin.app')

@section('title', translate('Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item"
                    aria-current="page">{{translate('Details')}}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title"></h1>
                </div>
            </div>
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active"
                           @if(isset($user) && $user->type == 1)
                           href="{{route('admin.agent.view',[$user['id']])}}"
                           @elseif(isset($user) && $user->type == 2)
                           href="{{route('admin.customer.view',[$user['id']])}}"
                           @else
                           href="#"
                            @endif
                        >{{translate('details')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           @if(isset($user) && $user->type == 1)
                           href="{{route('admin.agent.transaction',[$user['id']])}}"
                           @elseif(isset($user) && $user->type == 2)
                           href="{{route('admin.customer.transaction',[$user['id']])}}"
                           @else
                           href="#"
                            @endif
                        >{{translate('Transactions')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->


        <div class="row my-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-header text-capitalize">{{translate('wallet')}}<i style="font-size: 25px" class="tio-wallet"></i></div>
                    <div class="card-body">
                        <div class="card shadow h-100 for-card-body-3 badge-info"
                             style="background: #444941!important;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class=" font-weight-bold for-card-text text-uppercase mb-1">{{translate('balance')}}</div>
                                        <div
                                            class="for-card-count">{{ $user->emoney['current_balance']??0 }}
                                        </div>
                                    </div>
                                    <div class="col-auto for-margin">
                                        <i class="tio-money-vs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-header text-capitalize">{{translate('Personal Info')}}<i style="font-size: 25px" class="tio-info"></i></div>
                    <div class="card-body">
                        <div class="card-body"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="flex-start">
                                <div><h5>{{translate('name')}} : </h5></div>
                                <div class="mx-1"><span class="text-dark">{{$user['f_name']??''}} {{$user['l_name']??''}}</span></div>
                            </div>
                            <div class="flex-start">
                                <div><h5>{{translate('Phone')}} : </h5></div>
                                <div class="mx-1"><span class="text-dark">{{$user['phone']??''}}</span></div>
                            </div>
                            @if(isset($user['email']))
                                <div class="flex-start">
                                    <div><h5>{{translate('Email')}} : </h5></div>
                                    <div class="mx-1"><span class="text-dark">{{$user['email']}}</span></div>
                                </div>
                            @endif
                            @if(isset($user['identification_type']))
                                <div class="flex-start">
                                    <div><h5>{{translate('identification_type')}} : </h5></div>
                                    <div class="mx-1"><span class="text-dark">{{translate($user['identification_type'])}}</span></div>
                                </div>
                            @endif
                            @if(isset($user['identification_number']))
                                <div class="flex-start">
                                    <div><h5>{{translate('identification_number')}} : </h5></div>
                                    <div class="mx-1"><span class="text-dark">{{$user['identification_number']}}</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
