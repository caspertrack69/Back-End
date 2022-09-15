@extends('layouts.admin.app')

@section('title', translate('EMoney'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="align-items-center">
                <div class="col-sm mb-2 mb-sm-0 flex-between">
                    <h1 class="page-header-title">{{translate('EMoney')}}</h1>
                    <h1><i class="tio-user-switch"></i></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="row gx-2 gx-lg-3" id="order_stats">
                    @include('admin-views.emoney.partials._stats', ['data'=>$balance])
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body">
                <form action="{{route('admin.emoney.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Generate EMoney')}}</label>
                                <input type="number" id="amount" name="amount" step=".01" class="form-control" min="1"
                                       placeholder="{{ translate('EX: 100') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                            class="btn btn-primary">{{translate('Generate')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')

@endpush
