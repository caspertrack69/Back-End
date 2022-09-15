<?php

namespace App\Http\Controllers;

//use App\Model\Order;
use App\Models\EMoney;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $unused_balance = EMoney::with('user')->whereHas('user', function ($q) {
            $q->where('type', '=', ADMIN_TYPE);
        })->sum('current_balance');

        if($request->amount > $unused_balance) {
            Toastr::error(translate('The requested amount is too big'));
        }

        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

//        if ($request->has('callback')) {
//            Order::where(['id' => $request->order_id])->update(['callback' => $request['callback']]);
//        }

        session()->put('amount', $request->amount);
        session()->put('user_id', $request['user_id']);

        $user = User::where('type', '!=', 0)->find($request['user_id']);

        if (isset($user)) {
            return view('payment-view');
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);

    }

    public function success()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
