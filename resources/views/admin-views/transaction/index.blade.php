@extends('layouts.admin.app')

@section('title', translate('transaction List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0 flex-between">
                    <h1 class="page-header-title">{{translate('transaction')}}</h1>
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
                            <h5 class="card-header-title text-primary mx-1">({{ $transactions->total() }})</h5>
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
                                <th>{{translate('Transaction Id')}}</th>
                                <th>{{translate('Sender')}}</th>
                                <th>{{translate('Receiver')}}</th>
                                <th>{{translate('Debit')}}</th>
                                <th>{{translate('Credit')}}</th>
                                <th>{{translate('Type')}}</th>
                                <th>{{translate('Balance')}}</th>
                                <th>{{translate('Time')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($transactions as $key=>$transaction)
                                <tr>
                                    <td>{{$transactions->firstitem()+$key}}</td>
                                    <td>{{ $transaction->transaction_id??'' }}</td>
                                    <td>
                                        @php($sender_info = Helpers::get_user_info($transaction['from_user_id']))
                                        @if($sender_info != null)
                                            <a href="{{route('admin.customer.view',[$transaction['to_user_id']])}}">
                                                {{ $sender_info->f_name ?? '' }} {{ $sender_info->phone ?? ''}}
                                            </a>
                                        @else
                                            <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php($receiver_info = Helpers::get_user_info($transaction['to_user_id']))
                                        @if($receiver_info != null)
                                            <a href="{{route('admin.customer.view',[$transaction['to_user_id']])}}">
                                                {{ $receiver_info->f_name ?? '' }} {{ $receiver_info->phone ?? '' }}
                                            </a>
                                        @else
                                            <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="">
                                            {{ Helpers::set_symbol($transaction['debit']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="">
                                            {{ Helpers::set_symbol($transaction['credit']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-uppercase text-muted badge badge-light">{{ translate($transaction['transaction_type']) }}</span>
                                    </td>
                                    <td>
                                        <span class="">{{ Helpers::set_symbol($transaction['balance']) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted badge badge-light">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $transactions->links() !!}
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
    <script>
        $("#receiver").select2({
            ajax: {
                url: '{{route('admin.transaction.get_user')}}',
                type: "get",
                data: function (params) {
                    var receiver_type = $('#receiver_type').val();
                    if (receiver_type == null) {
                        swal('{{translate('Select_valid_receiver_type_first')}}');
                    }
                    console.log("type: " + receiver_type);
                    return {
                        q: params.term, // search term
                        page: params.page,
                        receiver_type: receiver_type
                    };

                },
                processResults: function (data) {
                    console.log("data: " + data);
                    return {
                        results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>
@endpush
