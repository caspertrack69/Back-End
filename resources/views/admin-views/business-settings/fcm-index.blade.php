@extends('layouts.admin.app')

@section('title', translate('FCM Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('Firebase Push Notification Setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.update-fcm'):'javascript:'}}"
                            method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @php($key=\App\Models\BusinessSetting::where('key','push_notification_key')->first())
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('server')}} {{translate('key')}}</label>
                                <textarea name="push_notification_key" class="form-control"
                                          required>{{env('APP_MODE')!='demo'?$key->value??'':''}}</textarea>
                            </div>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <hr>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">

                <div class="card">
                    <div class="card-header">
                        <h2>{{translate('push')}} {{translate('messages')}}</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.update-fcm-messages')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- customer transfer Message--}}
                                @php($data = \App\CentralLogics\Helpers::get_business_settings('money_transfer_message'))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="money_transfer_status">
                                            <input type="checkbox" name="money_transfer_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="money_transfer_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('EMoney Transfer Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="money_transfer_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings(CASH_IN))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="cash_in_status">
                                            <input type="checkbox" name="cash_in_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="cash_in_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Cash In Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="cash_in_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings(CASH_OUT))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="cash_out_status">
                                            <input type="checkbox" name="cash_out_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="cash_out_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Cash Out Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="cash_out_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings(SEND_MONEY))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="send_money_status">
                                            <input type="checkbox" name="send_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="send_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Send Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="send_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings('request_money'))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="request_money_status">
                                            <input type="checkbox" name="request_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="request_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Request Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="request_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings('approved_money'))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="approved_money_status">
                                            <input type="checkbox" name="approved_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="approved_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Approved Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="approved_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings('denied_money'))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="denied_money_status">
                                            <input type="checkbox" name="denied_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="denied_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Denied Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="denied_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings(ADD_MONEY))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="add_money_status">
                                            <input type="checkbox" name="add_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="add_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Add Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="add_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = \App\CentralLogics\Helpers::get_business_settings(RECEIVED_MONEY))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="toggle-switch d-flex align-items-center mb-3"
                                               for="received_money_status">
                                            <input type="checkbox" name="received_money_status"
                                                   class="toggle-switch-input"
                                                   value="1"
                                                   id="received_money_status" {{$data?($data['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                              </span>
                                            <span class="toggle-switch-content">
                                                <span
                                                    class="d-block">{{translate('Received Money Message')}}</span>
                                              </span>
                                        </label>

                                        <textarea name="received_money_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                            </div>

                            <button type="submit"
                                    class="btn btn-primary">{{translate('submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
