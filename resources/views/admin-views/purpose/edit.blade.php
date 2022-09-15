@extends('layouts.admin.app')

@section('title', translate('Edit Title'))

@push('css_or_js')

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
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.purpose.update', ['id'=>$purpose->id])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('name') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ $purpose->title??'' }}"
                                       placeholder="{{translate('New Title')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('color') }}<small
                                        style="color: red">*
                                        ( {{ translate('choose_in_HEXA_format') }} )</small></label>
                                <input type="color" name="color" class="form-control" value="{{ $purpose->color??'' }}"
                                       placeholder="{{translate('Hexa color code')}}" required>
                            </div>
                        </div>
                        <div class="col-12 from_part_2">
                            <label>{{ translate('image') }}</label><small style="color: red">*
                                ( {{ translate('ratio 1:1 ') }} )</small>
                            <div class="custom-file">
                                <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">
                                    {{ translate('choose file') }}</label>
                            </div>
                        </div>
                        <div class="col-12 from_part_2">
                            <div class="form-group">
                                <div class="text-center mt-3">
                                    <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                         onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                         src="{{asset('storage/app/public/purpose') . '/' . $purpose->logo}}"
                                         {{--src="{{asset('storage/app/public/purpose')}}/{{$purpose['logo']??''}}"--}}
                                         alt="image"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" class="form-control" value="{{ $purpose->id }}">

                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                </form>
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
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
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
