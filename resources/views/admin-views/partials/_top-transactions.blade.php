<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-star"></i> {{translate('Top Transactions')}}
    </h5>
    <i class="tio-american-express" style="font-size: 40px"></i>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tbody>
                @foreach($top_transactions as $key=>$top_transaction)
                    @if(isset($top_transaction->user))
                        <tr
                            @if($top_transaction->user['type']==2)
                            onclick="location.href='{{route('admin.customer.view',[$top_transaction->user['id']])}}'"
                            @endif
                            style="cursor: pointer">
                            <td scope="row">
                                <img height="35" style="border-radius: 5px"
                                     src="{{asset('storage/app/public')}}/{{ $top_transaction->user['type']==1?'agent':'customer' }}/{{ $top_transaction->user['image'] ?? '' }}"
                                     onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                     alt="{{$top_transaction->user->phone}} image">
                                <span class="ml-2">
                                    {{ Str::limit($top_transaction->user->f_name . ' (' . $top_transaction->user->phone . ')', 20) }}
                            </span>
                            </td>
                            <td>
                          <span style="font-size: 18px">
                            {{ Helpers::set_symbol($top_transaction['total_transaction']) }}
                          </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End Body -->
