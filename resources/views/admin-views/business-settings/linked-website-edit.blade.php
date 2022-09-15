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
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.linked-website')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('name')}}</label>
                                <input type="text" name="name" class="form-control" value="{{ $linked_website['name'] }}"
                                       placeholder="{{translate('example')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('URL')}}</label>
                                <input type="text" name="url" class="form-control" value="{{ $linked_website['url'] }}"
                                       placeholder="{{translate('""_www.example.com')}}" required>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label>{{translate('image')}}</label><small style="color: red">* ( {{translate('ratio')}} 1:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                <label class="custom-file-label" for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                            </div>
                            <center class="mt-4">
                                <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                     src="{{asset('storage/app/public/website')}}/{{$linked_website['image']}}"
                                     onerror="this.src='{{asset('public/assets/admin/img/1920x400/img2.jpg')}}'"
                                     alt="delivery-man image"/>
                            </center>
                        </div>
                    </div>
                    <input type="hidden" name="id" class="form-control" value="{{ $linked_website['id'] }}">

                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>
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
