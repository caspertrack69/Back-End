@extends('layouts.admin.app')

@section('title', translate('Agent List'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-filter-list"></i> {{translate('Agent')}} {{translate('list')}}
                    </h1>
                </div>
                <a href="{{route('admin.agent.add')}}" class="btn btn-primary pull-right mr-3"><i
                        class="tio-add-circle"></i> {{translate('Add')}} {{translate('Agent')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Agent Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $agents->total() }})</h5>
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
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#NO')}}</th>
                                <th style="width: 15%">{{translate('image')}}</th>
                                <th style="width: 30%">{{translate('name')}}</th>
                                <th>{{translate('phone')}}</th>
                                <th>{{translate('email')}}</th>
                                <th>{{translate('status')}}</th>
                                <th>{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($agents as $key=>$agent)
                                <tr>
                                    <td>{{$agents->firstitem()+$key}}</td>
                                    <td>
                                        <img class="rounded-circle" height="60px" width="60px" style="cursor: pointer"
                                             onclick="location.href='{{route('admin.customer.view',[$agent['id']])}}'"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{asset('storage/app/public/agent')}}/{{$agent['image']}}">
                                    </td>
                                    <td>
                                        <a href="{{route('admin.agent.view',[$agent['id']])}}" class="d-block font-size-sm text-body">
                                            {{$agent['f_name'].' '.$agent['l_name']}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$agent['phone']}}
                                    </td>
                                    <td>
                                        @if(isset($agent['email']))
                                            <a href="mailto:{{ $agent['email'] }}" class="text-primary">{{ $agent['email'] }}</a>
                                        @else
                                            <span class="badge-pill badge-soft-dark text-muted">Email unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="toggle-switch d-flex align-items-center mb-3" for="welcome_status_{{$agent['id']}}">
                                            <input type="checkbox" name="welcome_status"
                                                   class="toggle-switch-input"
                                                   id="welcome_status_{{$agent['id']}}" {{$agent?($agent['is_active']==1?'checked':''):''}}
                                                   onclick="location.href='{{route('admin.agent.status',[$agent['id']])}}'">

                                            <span class="toggle-switch-label p-1">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn-sm btn-primary p-1 m-1"
                                           href="{{route('admin.agent.view',[$agent['id']])}}">
                                            <i class="fa fa-eye pl-1" aria-hidden="true"></i>
                                        </a>
                                        <a class="btn-sm btn-secondary p-1 pr-2 m-1"
                                           href="{{route('admin.agent.edit',[$agent['id']])}}">
                                            <i class="fa fa-pencil pl-1" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $agents->links() !!}
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
