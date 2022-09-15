@extends('layouts.admin.app')

@section('title', translate('Transfer List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0 flex-between">
                    <h1 class="page-header-title">{{translate('Transfer')}}</h1>
                    <h1><i class="tio-user-switch"></i></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body mx-3">
                <form action="{{route('admin.transfer.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
{{--                        <div class="col-md-3 col-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="input-label"--}}
{{--                                       for="exampleFormControlInput1translate('Sender')}}</label>--}}
{{--                                <select name="from_user_id" class="form-control js-select2-custom" required>--}}
{{--                                    --}}{{--<option value="" selected disabledtranslate('Select Sender')}}</option>--}}
{{--                                    <option value="1translate('Admin')}}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Receiver Type')}}</label>
                                <select name="receiver_type" class="form-control js-select2-custom" id="receiver_type"
                                        required>
                                    <option value="" selected
                                            disabled>{{translate('Select Type')}}</option>
                                    <option value="1">{{translate('Agent')}}</option>
                                    <option value="2">{{translate('Customer')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Receiver')}}</label>

                                <select name="to_user_id" class="form-control js-data-example-ajax" id="receiver"
                                        data-placeholder="{{translate('Choose')}}"
                                        required>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Amount')}}</label>
                                <input type="number" name="amount" class="form-control" min="1" max="{{$unused_balance}}"
                                       placeholder="{{translate('Ex : 9999')}}"
                                       required>
                                @if($unused_balance > 0)
                                    <small class="w-100">{{ translate('The amount must be less than or equal to ') . $unused_balance}}</small>
                                @else
                                    <small class="w-100">{{ translate('The amount is too low to transfer') }}</small>
                                @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                            class="btn btn-primary">{{translate('Transfer')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Transfer Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $transfers->total() }})</h5>
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
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('No#')}}</th>
                                <th>{{translate('Receiver')}}</th>
                                <th>{{translate('Receiver Type')}}</th>
                                <th>{{translate('amount')}}</th>
                                <th>{{translate('Time')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($transfers as $key=>$transfer)
                                <tr>
                                    <td>
                                        {{$transfers->firstitem()+$key}}
                                    </td>
                                    <td>
                                        @php($user_info = \App\CentralLogics\Helpers::get_user_info($transfer->receiver))
                                        @if(isset($user_info))
                                            <a href="{{route('admin.customer.view',[$user_info['id']])}}">{{ $user_info->f_name . ' ' . $user_info->l_name }}</a>
                                        @else
                                            <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transfer->receiver_type == 1)
                                            <span class="text-uppercase badge badge-light text-muted">{{translate('Agent')}}</span>
                                        @elseif($transfer->receiver_type == 2)
                                            <span class="text-uppercase badge badge-light text-muted">{{translate('Customer')}}</span>
                                        @endif
                                    </td>
                                    <td class="amount-column">
                                        <span class="">
                                            {{ Helpers::set_symbol($transfer['amount']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted badge badge-light">{{ $transfer->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $transfers->links() !!}
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
                url: '{{route('admin.transfer.get_user')}}',
                type: "get",
                data: function (params) {
                    var receiver_type = $('#receiver_type').val();
                    if (receiver_type == null) {
                        swal('{{translate('Select_valid_receiver_type_first')}}');
                    }
                    // console.log("type: " + receiver_type);
                    return {
                        q: params.term, // search term
                        page: params.page,
                        receiver_type: receiver_type
                    };

                },
                processResults: function (data) {
                    // console.log("data: " + data);
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

        $('#receiver_type').on('change', function() {
            $('#receiver').empty();
        });
    </script>
@endpush
