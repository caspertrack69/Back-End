@extends('layouts.admin.app')

@section('title', translate('Update Customer'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-edit"></i> {{translate('Update Customer')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 card card-body">
                <form action="{{route('admin.customer.update',[$customer['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('First Name')}}</label>
                                <input type="text" name="f_name" class="form-control" value="{{ $customer['f_name']??'' }}"
                                       placeholder="{{translate('First Name')}}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                                <input type="text" name="l_name" class="form-control" value="{{ $customer['l_name']??'' }}"
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
                                    <small>({{translate('optional')}})</small></label>
                                <input type="email" name="email" class="form-control" value="{{ $customer['email']??'' }}"
                                       placeholder="{{translate('Ex : ex@example.com')}}">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <input type="text" name="phone" class="form-control" value="{{ $customer['phone']??'' }}"
                                       placeholder="{{translate('Ex : 017********')}}"
                                       required disabled>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Gender')}}</label>
                                <select name="gender" class="form-control">
                                    <option value="" selected
                                            disabled>{{translate('Select Gender')}}</option>
                                    <option value="male">{{translate('Male')}}</option>
                                    <option value="female">{{translate('Female')}}</option>
                                    <option value="other">{{translate('Other')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('Occupation')}}</label>
                                <input type="text" name="occupation" class="form-control"
                                       value="{{ $customer['occupation']??'' }}"
                                       placeholder="{{translate('Ex : Businessman')}}"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="input-label"
                                   for="exampleFormControlInput1">{{translate('PIN')}}</label>
                            <input type="text" name="password" class="form-control" value=""
                                   placeholder="{{translate('4digit PIN')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{translate('Agent')}} {{translate('image')}}</label><small
                            style="color: red">* ( {{translate('ratio')}} 1:1 )</small>
                        <div class="custom-file">
                            <input type="file"  accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" name="image" id="file"  onchange="loadFile(event)" style="display: none;">
                            <label style="cursor: pointer" class="custom-file-label" for="file">{{translate('choose file')}}</label>
                        </div>

                        <div class="text-center mt-3">
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                 src="{{asset('storage/app/public/customer').'/'.$customer['image']}}" alt="customer image"/>
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
        var loadFile = function(event) {
            var image = document.getElementById('viewer');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
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
