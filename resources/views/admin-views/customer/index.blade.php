@extends('layouts.admin.app')

@section('title', translate('Add New Customer'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-add-circle-outlined"></i> {{translate('Add New Customer')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body">
                <form action="{{route('admin.customer.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('First Name')}}</label>
                                <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"
                                       placeholder="{{translate('First Name')}}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                                <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"
                                       placeholder="{{translate('Last Name')}}"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('email')}}
                                    <small class="text-muted">({{translate('optional')}})</small></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                       placeholder="{{translate('Ex : ex@example.com')}}">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('phone')}}<small class="text-danger"> *{{ translate('Must use country code') }}</small></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                       placeholder="{{translate('Ex : +88017********')}}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Gender')}}</label>
                                <select name="gender" class="form-control" required>
                                    <option value="" selected
                                            disabled>{{translate('Select Gender')}}</option>
                                    <option value="male" {{ (old("gender") == 'male' ? "selected":"") }}>{{translate('Male')}}</option>
                                    <option value="female" {{ (old("gender") == 'female' ? "selected":"") }}>{{translate('Female')}}</option>
                                    <option value="other" {{ (old("gender") == 'other' ? "selected":"") }}>{{translate('Other')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Occupation')}}</label>
                                <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}"
                                       placeholder="{{translate('Ex : Businessman')}}"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="input-label"
                                   for="exampleFormControlInput1">{{translate('PIN')}}</label>
                            <input type="text" name="password" class="form-control" value="{{ old('password') }}"
                                   placeholder="{{translate('4digit PIN')}}"
                                   required maxlength="4">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-dark">{{translate('Customer Image')}}</label><small
                            class="text-danger"> *( {{translate('ratio 1:1')}} )</small>
                        <div class="custom-file">
                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                            <label class="custom-file-label text-muted"
                                   for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                        </div>

                        <div class="text-center mt-3">
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}" alt="customer image"/>
                        </div>
                    </div>

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

    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-2',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
