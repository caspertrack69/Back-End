@extends('layouts.admin.app')

@section('title', translate('Add New Notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-notifications"></i> {{translate('notification')}}</h1>
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body mx-3">
                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-12 col-md-9">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                            <input type="text" name="title" class="form-control" placeholder="{{translate('New Notification')}}" required>
                        </div>

                        <div class="col-12 col-md-3 mt-4 mt-md-0">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('receiver')}}</label>
                            <select name="receiver" class="form-control js-select2-custom" id="receiver" required>
                                <option value="all" selected>{{translate('All')}}</option>
                                <option value="customers">{{translate('Customers')}}</option>
                                <option value="agents">{{translate('Agents')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="text-dark">{{translate('image')}}</label><small style="color: red"> ({{translate('ratio 3:1')}})</small>
                        <div class="custom-file">
                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                        </div>
                        <div class="text-center mt-3">
                            <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" alt="image"/>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{translate('send')}} {{translate('notification')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header flex-between">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Notification Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $notifications->total() }})</h5>
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
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('title')}}</th>
                                <th style="width: 50%">{{translate('description')}}</th>
                                <th>{{translate('receiver')}}</th>
                                <th>{{translate('image')}}</th>
{{--                                <th>{{translate('status')}}</th>--}}
                                <th style="width: 10%">{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$notifications->firstitem()+$key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{substr($notification['title'],0,25)}} {{strlen($notification['title'])>25?'...':''}}
                                    </span>
                                    </td>
                                    <td>
                                        {{substr($notification['description'],0,25)}} {{strlen($notification['description'])>25?'...':''}}
                                    </td>
                                    <td>
                                        @if($notification['receiver'] == 'all')
                                            <span class="text-uppercase badge badge-light text-muted">{{translate('all')}}</span>
                                        @elseif($notification['receiver'] == 'customers')
                                            <span class="text-uppercase badge badge-light text-muted">{{translate('customers')}}</span>
                                        @elseif($notification['receiver'] == 'agents')
                                            <span class="text-uppercase badge badge-light text-muted">{{translate('agents')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification['image']!=null)
                                            <img style="height: 75px" class="shadow-image"
                                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                                 src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}">
                                        @else
                                            <label class="badge badge-light text-muted">{{translate('No image available')}}</label>
                                        @endif
                                    </td>
{{--                                    <td>--}}
{{--                                        @if($notification['status']==1)--}}
{{--                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"--}}
{{--                                                 onclick="location.href='{{route('admin.notification.status',[$notification['id'],0])}}'">--}}
{{--                                                <span class="legend-indicator bg-success"></span>{{translate('active')}}--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"--}}
{{--                                                 onclick="location.href='{{route('admin.notification.status',[$notification['id'],1])}}'">--}}
{{--                                                <span class="legend-indicator bg-danger"></span>{{translate('disabled')}}--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.notification.edit',[$notification['id']])}}">{{translate('edit & resend')}}</a>
                                                <a class="dropdown-item" href="javascript:"
                                                   onclick="$('#notification-{{$notification['id']}}').submit()">{{translate('delete')}}</a>
                                                <form
                                                    action="{{route('admin.notification.delete',[$notification['id']])}}"
                                                    method="post" id="notification-{{$notification['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $notifications->links() !!}
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
