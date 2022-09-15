<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EMoneyController extends Controller
{
    public function index()
    {
        $used_balance = EMoney::with('user')->whereHas('user', function ($q) {
            $q->where('type', '!=', 0);
        })->sum('current_balance');

        $unused_balance = EMoney::with('user')->whereHas('user', function ($q) {
            $q->where('type', '=', 0);
        })->sum('current_balance');

        $total_balance = $used_balance + $unused_balance;
        $charge_earned = EMoney::with('user')->find(Auth::id())->charge_earned ?? 0;

        $balance = [];
        $balance['total_balance'] = $total_balance;
        $balance['used_balance'] = $used_balance;
        $balance['unused_balance'] = $unused_balance;
        $balance['total_earned'] = $charge_earned;
        return view('admin-views.emoney.index', compact('balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|min:0|not_in:0',
        ],
        [
            'amount.not_in' => translate('Amount must be greater than zero!'),
        ]);

        /////

        //START TRANSACTION
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = Helpers::get_admin_id();
        $data['to_user_id'] = $data['from_user_id'];    //since eMoney generation


        try {
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'credit';
            $data['transaction_type'] = CASH_IN;
            $data['ref_trans_id'] = null;
            $data['amount'] = $request->amount;

            $admin_transaction = Helpers::make_transaction($data);

            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            if ($admin_transaction == null) {
                throw new TransactionFailedException('Transaction from receiver is failed');
            }

            DB::commit();
            Toastr::success(translate('EMoney generated successfully!'));

        } catch (TransactionFailedException $e) {
            DB::rollBack();
            Toastr::error(translate('Something went wrong!'));
        }



//        try {
//            $emoney = EMoney::firstOrNew(['user_id' => Auth::id()]);
//            $emoney->user_id = Auth::id();
//            $emoney->current_balance += $request->amount;
//            $emoney->save();
//            Toastr::success(translate('EMoney generated successfully!'));
//
//        } catch (\Exception $e) {
//            Toastr::error(translate('Something went wrong!'));
//        }

        return back();
    }
}
