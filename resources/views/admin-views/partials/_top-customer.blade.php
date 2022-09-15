<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-user-big-outlined"></i> {{translate('Top Customers')}}
    </h5>
    <i class="tio-poi-user" style="font-size: 40px"></i>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        @foreach($top_customers as $key=>$top_customer)
            @if(isset($top_customer->user))
                <div class="col-6 col-md-4 mt-2"
                     onclick="location.href='{{route('admin.customer.view', [$top_customer['user_id']])}}'"
                     style="padding-left: 6px;padding-right: 6px;cursor: pointer">
                    <div class="grid-card" style="min-height: 170px">
                        <label class="label_1">{{ translate('Total Transaction') }} : <br/> {{ Helpers::set_symbol($top_customer['total_transaction']) }}</label>
                        <center class="mt-6">
                            <img style="border-radius: 50%;width: 60px;height: 60px;border:2px solid #80808082;"
                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                 src="{{asset('storage/app/public/customer'.'/'. $top_customer->user['image']  ?? '' )}}">
                        </center>
                        <div class="text-center mt-2">
                            <span style="font-size: 10px">{{$top_customer->user['f_name']??'Not exist'}}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
