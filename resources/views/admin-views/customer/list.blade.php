@extends('layouts.admin.app')

@section('title', translate('Customer List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center mb-3">
                <div class="col-sm flex-between">
                    <h1 class="page-header-title"><i
                            class="tio-user-add"></i> {{translate('customers')}}
                    </h1>
                    <a href="{{route('admin.customer.add')}}" class="btn btn-primary pull-right mr-1"><i
                            class="tio-add-circle"></i> {{translate('Add')}} {{translate('Customer')}}
                    </a>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="flex-start">
                    <h5 class="card-header-title">{{translate('Customer Table')}}</h5>
                    <h5 class="card-header-title text-primary mx-1">({{ $customers->total() }})</h5>
                </div>
                <div>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                   class="form-control"
                                   placeholder="{{translate('Search')}}" aria-label="Search"
                                   value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="width: 100%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('#')}}</th>
                        <th style="width: 15%">{{translate('image')}}</th>
                        <th style="width: 30%">{{translate('name')}}</th>
                        <th>{{translate('phone')}}</th>
                        <th>{{translate('email')}}</th>
                        <th>{{translate('status')}}</th>
                        <th>{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>{{$customers->firstitem()+$key}}</td>
                            <td>
                                <img class="rounded-circle" height="60px" width="60px" style="cursor: pointer"
                                     onclick="location.href='{{route('admin.customer.view',[$customer['id']])}}'"
                                     onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                     src="{{asset('storage/app/public/customer')}}/{{$customer['image']}}">
                            </td>
                            <td>
                                <a class="d-block font-size-sm text-body"
                                   href="{{route('admin.customer.view',[$customer['id']])}}">
                                    {{$customer['f_name'].' '.$customer['l_name']}}
                                </a>
                            </td>
                            <td>
                                {{$customer['phone']}}
                            </td>
                            <td>
                                @if(isset($customer['email']))
                                    <a href="mailto:{{ $customer['email'] }}" class="text-primary">{{ $customer['email'] }}</a>
                                @else
                                    <span class="text-muted badge badge-danger text-dark">{{ translate('Email unavailable') }}</span>
                                @endif
                            </td>
                            <td>
                                <label class="toggle-switch d-flex align-items-center mb-3" for="welcome_status_{{$customer['id']}}">
                                    <input type="checkbox" name="welcome_status"
                                           class="toggle-switch-input"
                                           id="welcome_status_{{$customer['id']}}" {{$customer?($customer['is_active']==1?'checked':''):''}}
                                           onclick="location.href='{{route('admin.customer.status',[$customer['id']])}}'">

                                    <span class="toggle-switch-label p-1">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <a class="btn-sm btn-primary p-1 m-1"
                                   href="{{route('admin.customer.view',[$customer['id']])}}">
                                    <i class="fa fa-eye pl-1" aria-hidden="true"></i>
                                </a>
                                <a class="btn-sm btn-secondary p-1 pr-2 m-1"
                                   href="{{route('admin.customer.edit',[$customer['id']])}}">
                                    <i class="fa fa-pencil pl-1" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $customers->links() !!}
                            <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')

@endpush
