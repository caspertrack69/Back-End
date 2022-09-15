@extends('layouts.admin.app')

@section('title', translate('Update Banner'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-image"></i> {{translate('banner')}} {{translate('update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.banner.update',[$banner['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-12">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                            <input type="text" name="title" class="form-control" placeholder="{{translate('title')}}" value="{{$banner['title']}}" required>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('URL')}}</label>
                            <input type="text" name="url" class="form-control" placeholder="{{translate('URL')}}" value="{{$banner['url']}}"  required>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('receiver')}}</label>
                            <select name="receiver" class="form-control js-select2-custom" id="receiver">
                                <option value="" selected disabled>{{translate('Update receiver')}}</option>
                                <option value="all">{{translate('All')}}</option>
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
                                     src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}"
                                     onerror="this.src='{{asset('public/assets/admin/img/1920x400/img2.jpg')}}'"
                                     alt="image"/>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">{{translate('update')}}</button>
                </form>
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
