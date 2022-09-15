@extends('layouts.admin.app')

@section('title', translate('Linked Website'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-add-circle-outlined"></i> {{translate('Add New Website')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body mx-3">
                <form action="{{route('admin.linked-website')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('name')}}</label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="{{translate('example')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('URL')}}</label>
                                <input type="text" name="url" class="form-control"
                                       placeholder="{{translate('""_www.example.com')}}" required>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label class="text-dark">{{translate('image')}}</label><small style="color: red">*
                                ( {{translate('ratio 1:1')}} )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                <label class="custom-file-label"
                                       for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                            </div>
                            <center class="mt-4">
                                <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                     src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}"
                                     alt="delivery-man image"/>
                            </center>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header ml-3">
                        <div class="row">
                            <div class="">
                                <h5>{{translate('Linked Website Table')}}
                                    <span class="text-primary">({{ $linked_websites->total() }})</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th style="width: 50%">{{translate('name')}}</th>
                                <th style="width: 50%">{{translate('URL')}}</th>
                                <th style="width: 50%">{{translate('image')}}</th>
                                <th style="width: 50%">{{translate('Status')}}</th>
                                <th style="width: 10%">{{translate('action')}}</th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($linked_websites as $key=>$linked_website)
                                <tr>
                                    <td>{{$linked_websites->firstItem()+$key}}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{$linked_website['name']}}
                                        </span>
                                    </td>
                                    <td>{{$linked_website['url']}}</td>
                                    <td>
                                        <div style="height: 60px; width: 60px;">
                                            <img class="shadow-image"
                                                src="{{asset('storage/app/public/website')}}/{{$linked_website['image']}}"
                                                style="width: 100%;height: auto"
                                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="toggle-switch d-flex align-items-center mb-3" for="welcome_status_{{$linked_website['id']}}">
                                            <input type="checkbox" name="welcome_status"
                                                   class="toggle-switch-input"
                                                   id="welcome_status_{{$linked_website['id']}}" {{$linked_website?($linked_website['status']==1?'checked':''):''}}
                                                   onclick="location.href='{{route('admin.linked-website-status',[$linked_website['id']])}}'">

                                            <span class="toggle-switch-label p-1">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.linked-website-edit',[$linked_website['id']])}}">{{translate('edit')}}</a>

                                                <a class="dropdown-item"
                                                   href="{{route('admin.linked-website-delete',['id'=>$linked_website['id']])}}">{{translate('delete')}}</a>
                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table>
                            <tfoot>
                            {!! $linked_websites->links() !!}
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
