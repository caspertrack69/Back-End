@extends('layouts.admin.app')

@section('title', translate('Add Purpose'))

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
                            class="tio-add-circle-outlined"></i> {{translate('add purpose')}}
                    </h1>
                </div>
            </div>
            <div class="alert alert-primary py-1 my-1 text-center" role="alert">
                {{ translate('Customers can use these purposes when they will send money or request money') }}
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body mx-3">
                <form action="{{route('admin.purpose.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('name') }}</label>
                                <input type="text" name="title" class="form-control"
                                       placeholder="{{translate('New Title')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('color') }}<small
                                        style="color: red">*
                                        ( {{ translate('choose_in_HEXA_format') }} )</small></label>
                                <input type="color" name="color" class="form-control"
                                       placeholder="{{translate('Hexa color code')}}" required>
                            </div>
                        </div>
                        <div class="col-12 from_part_2">
                            <label class="text-dark">{{ translate('image') }}</label><small style="color: red">*
                                ( {{ translate('ratio 1:1 ') }} )</small>
                            <div class="custom-file">
                                <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                <label class="custom-file-label" for="customFileEg1">
                                    {{ translate('choose file') }}</label>
                            </div>
                        </div>
                        <div class="col-12 from_part_2">
                            <div class="form-group">
                                <div class="text-center mt-3">
                                    <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                         src="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" alt="image"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Purpose Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $purposes->total() }})</h5>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th style="width: 50%">{{translate('Title')}}</th>
                                <th style="width: 20%">{{translate('Color')}}</th>
                                <th style="width: 20%">{{translate('Logo')}}</th>
                                <th style="width: 20%">{{translate('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($purposes as $key=>$purpose)
                                <tr>
                                    <td>{{$purposes->firstitem()+$key}}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{$purpose['title']}}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-pill"
                                             style="height: 1em; width: 4em; background-color: {{$purpose['color']??''}}"></div>
                                    </td>
                                    <td>
                                        <img width="auto" height="60" class="shadow-image"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{asset('storage/app/public/purpose')}}/{{$purpose['logo']}}">
                                    </td>
                                    <td>
                                        <a href="{{route('admin.purpose.edit', ['id'=>$purpose['id']])}}"
                                           class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <button
                                            onclick="delete_purpose('{{route('admin.purpose.delete', ['id'=>$purpose['id']])}}')"
                                            class="btn btn-secondary btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $purposes->links() !!}
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
        function delete_purpose($route) {
            swal({
                title: "Are you sure?",
                text: "You won't be able to revert this !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
            })
                .then((result) => {
                    console.log(result);
                    if (result.value === true) {
                        window.location.href = $route;
                    }
                });
        }
    </script>
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
