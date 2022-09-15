@extends('layouts.admin.app')

@section('title', translate('Agent request money'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0 flex-between">
                    <h1 class="page-header-title">{{translate('Agent Requested Transactions')}}</h1>
                    <h1><i class="tio-user-switch"></i></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header flex-between">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('transaction Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $request_money->total() }})</h5>
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
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('No#')}}</th>
                                <th>{{translate('Agent')}}</th>
                                <th>{{translate('Requested Amount')}}</th>
                                <th>{{translate('Note')}}</th>
                                <th>{{translate('Status')}}</th>
                                <th>{{translate('Requested time')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($request_money as $key=>$items)
                                <tr>
                                    <td>
                                        {{$request_money->firstitem()+$key}}
                                    </td>
                                    <td>
                                        @php($user = Helpers::get_user_info($items->from_user_id))
                                        @if(isset($user))
                                            <span class="d-block font-size-sm text-body">
                                                <a href="{{route('admin.customer.view',[$user->id])}}">
                                                    {{ $user->f_name . ' ' . $user->l_name }}
                                                </a>
                                            </span>
                                        @else
                                            <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ Helpers::set_symbol($items->amount) }}</td>
                                    <td style="width: 30%">{{ $items->note }}</td>
                                    <td>
                                        @if(isset($user))
                                            @if( $items->type == 'pending' )
                                                <div>
                                                    {{--<span class="btn btn-warning btn-sm" style="cursor: default"> {{translate('Pending')}}</span>--}}
                                                    <a href="{{ route('admin.transaction.request_money_status_change', ['approve', 'id'=>$items->id]) }}" class="btn btn-primary btn-sm"> {{translate('Approve')}}</a>
                                                    <a href="{{ route('admin.transaction.request_money_status_change', ['deny', 'id'=>$items->id]) }}" class="btn btn-warning btn-sm"> {{translate('Deny')}}</a>
                                                </div>
                                            @elseif( $items->type == 'approved' )
                                                <span class="badge badge-success"> {{translate('Approved')}}</span>
                                            @elseif( $items->type == 'denied' )
                                                <span class="badge badge-danger"> {{translate('Denied')}}</span>
                                            @endif
                                        @else
                                            @if( $items->type == 'pending' )
                                                <div data-toggle="tooltip" data-placement="left" title="{{translate('User unavailable') }}">
                                                    {{--<span class="btn btn-warning btn-sm" style="cursor: default"> {{translate('Pending')}}</span>--}}
                                                    <a href="#" class="btn btn-primary btn-sm disabled"> {{translate('Approve')}}</a>
                                                    <a href="#" class="btn btn-warning btn-sm disabled"> {{translate('Deny')}}</a>
                                                </div>
                                            @elseif( $items->type == 'approved' )
                                                <span class="badge badge-success"> {{translate('Approved')}}</span>
                                            @elseif( $items->type == 'denied' )
                                                <span class="badge badge-danger"> {{translate('Denied')}}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td style="width: 10%">{{ $items->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $request_money->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
@endpush
