@extends('layouts.admin.app')

@section('title', translate('Add New Banner'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-image"></i> {{translate('banner')}}</h1>
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body mx-3">
                <form action="{{route('admin.banner.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                            <input type="text" name="title" class="form-control" placeholder="{{translate('title')}}" required>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('URL')}}</label>
                            <input type="text" name="url" class="form-control" placeholder="{{translate('URL')}}" required>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('receiver')}}</label>
                            <select name="receiver" class="form-control js-select2-custom" id="receiver" required>
                                <option value="all" selected>{{translate('All')}}</option>
                                <option value="customers">{{translate('Customers')}}</option>
                                <option value="agents">{{translate('Agents')}}</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label class="text-dark">{{translate('image')}}</label><small style="color: red; padding: 0 5px">* ( {{translate('ratio')}} 3:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                            </div>
                            <div class="text-center mt-4">
                                <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                     src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" alt="image"/>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">{{translate('Add Banner')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header flex-between">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Banner Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $banners->total() }})</h5>
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
                                <th style="width: 50%">{{translate('title')}}</th>
                                <th>{{translate('URL')}}</th>
                                <th>{{translate('image')}}</th>
                                <th>{{translate('status')}}</th>
                                <th>{{translate('receiver')}}</th>
                                <th style="width: 10%">{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($banners as $key=>$banner)
                                <tr>
                                    <td>{{$banners->firstitem()+$key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{substr($banner['title'],0,25)}} {{strlen($banner['title'])>25?'...':''}}
                                    </span>
                                    </td>
                                    <td>
                                        <a class="text-dark" href="{{ $banner['url'] }}">{{substr($banner['url'],0,25)}} {{strlen($banner['url'])>25?'...':''}}</a>
                                    </td>
                                    <td>
                                        @if($banner['image']!=null)
                                            <img style="height: 75px" class="shadow-image"
                                                 src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}"
                                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'">
                                        @else
                                            <label class="badge badge-soft-warning">{{translate('No Image')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="toggle-switch d-flex align-items-center mb-3" for="welcome_status_{{$banner['id']}}">
                                            <input type="checkbox" name="welcome_status"
                                                   class="toggle-switch-input"
                                                   id="welcome_status_{{$banner['id']}}" {{$banner?($banner['status']==1?'checked':''):''}}
                                                   onclick="location.href='{{route('admin.banner.status',[$banner['id']])}}'">

                                            <span class="toggle-switch-label p-1">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        @if(isset($banner['receiver']))
                                            <span class="badge badge-light text-muted" style="cursor: default">{{ translate($banner['receiver'] ?? '') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn-sm btn-primary p-1 pr-2 m-1"
                                           href="{{route('admin.banner.edit',[$banner['id']])}}">
                                            <i class="fa fa-pencil pl-1" aria-hidden="true"></i>
                                        </a>
                                        <a class="btn-sm btn-secondary p-1 pr-2 m-1"
                                           href="{{route('admin.banner.delete',[$banner['id']])}}">
                                            <i class="fa fa-trash-o pl-1" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $banners->links() !!}
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
