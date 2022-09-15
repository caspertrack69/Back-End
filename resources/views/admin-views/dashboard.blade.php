@extends('layouts.admin.app')

@section('title', translate('dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .grid-card {
            border: 2px solid #00000012;
            border-radius: 10px;
            padding: 10px;
        }

        .label_1 {
            position: absolute;
            font-size: 10px;
            background: #FF4C29;
            color: #ffffff;
            width: 146px;
            padding: 2px;
            font-weight: bold;
            border-radius: 6px;
            text-align: center;
        }

        .center-div {
            text-align: center;
            border-radius: 6px;
            padding: 6px;
            border: 2px solid #8080805e;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header"
             style="padding-bottom: 0!important;border-bottom: 0!important;margin-bottom: 1.25rem!important;">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('welcome')}}
                        , {{auth('user')->user()->f_name}}.</h1>
                    <p>{{ translate('welcome_to_6cash_admin_panel') }}</p>
                </div>
                <div class="col-sm mb-2 mb-sm-0" style="height: 25px">
                    <label class="badge badge-soft-success float-right">
                        {{ translate('Software Version') }} : {{ env('SOFTWARE_VERSION') }}
                    </label>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card card-body mb-3 mb-lg-5">
            <div class="gx-2 gx-lg-3 mb-2">
                <div class="flex-between">
                    <h4>{{translate('EMoney Statistics')}}</h4>
                    <h4><i style="font-size: 30px" class="tio-money-vs pr-1"></i></h4>
                </div>
            </div>
            <div class="row gx-2 gx-lg-3" id="order_stats">
                @include('admin-views.partials._stats', ['data'=>$balance])
            </div>
        </div>
        <!-- End Card -->

        <div class="row gx-2 gx-lg-3 mb-3 mb-lg-5">
            <div class="col-lg-12">

                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 mb-3 border-bottom">
                                <h5 class="card-header-title float-left mb-2">
                                    <i style="font-size: 30px" class="tio-chart-pie-1"></i>
                                    {{translate('Transaction statistics for business analytics')}}
                                </h5>
                                <!-- Legend Indicators -->
                                <h5 class="card-header-title float-right mb-2">
                                    {{translate('Yearly Transaction')}}
                                    <i style="font-size: 30px" class="tio-chart-bar-2"></i>
                                </h5>
                                <!-- End Legend Indicators -->
                            </div>
                        </div>
                        <!-- End Row -->

                        <canvas id="transactionChart" style="width: 100%; height: 50vh"></canvas>

                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->

        <div class="row gx-2 gx-lg-3 mb-3 mb-lg-5">
            <div class="col-lg-6  mb-3 mb-lg-0">
                <div class="card h-100">
                    @include('admin-views.partials._top-agent',['top_agents'=>$data['top_agents']])
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    @include('admin-views.partials._top-customer',['top_customers'=>$data['top_customers']])
                </div>
            </div>

            <div class="col-lg-6 mt-4">
                <div class="card h-100">
                    @include('admin-views.partials._top-transactions',['top_transactions'=>$data['top_transactions']])
                </div>
            </div>
        </div>
        <!-- End Row -->


        @endsection

        @push('script')
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
            <script
                src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
        @endpush


        @push('script_2')
            <script>
                var ctx = document.getElementById("transactionChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [{
                            label: 'Transaction',
                            data: [{{$transaction[1]}}, {{$transaction[2]}}, {{$transaction[3]}}, {{$transaction[4]}}, {{$transaction[5]}}, {{$transaction[6]}}, {{$transaction[7]}}, {{$transaction[8]}}, {{$transaction[9]}}, {{$transaction[10]}}, {{$transaction[11]}}, {{$transaction[12]}}],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        scales: {
                            xAxes: [{
                                ticks: {
                                    maxRotation: 90,
                                    minRotation: 80
                                },
                                gridLines: {
                                    offsetGridLines: true // à rajouter
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>

    @endpush
