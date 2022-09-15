<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\RequestMoney;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use function App\CentralLogics\translate;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);

            $users = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })->get()->pluck('id')->toArray();

            $transactions = Transaction::where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('from_user_id', $users)
                        ->orWhereIn('to_user_id', $users)
                        ->orWhere('transaction_id', 'like', "%{$value}%")
                        ->orWhere('transaction_type', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $transactions = new Transaction();
        }

        $transactions = $transactions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.transaction.index', compact('transactions', 'search'));
    }

    public function request_money(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);

            $users = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })->get()->pluck('id')->toArray();

            $request_money = RequestMoney::where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('from_user_id', $users)
                        ->orWhere('to_user_id', $users)
                        ->orWhere('type', 'like', "%{$value}%")
                        ->orWhere('amount', 'like', "%{$value}%")
                        ->orWhere('note', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $request_money = new RequestMoney();
        }


        $request_money = $request_money->where('to_user_id', Helpers::get_admin_id())->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return View('admin-views.transaction.request_money_list', compact('request_money', 'search'));
    }

    public function request_money_status_change(Request $request, $slug)
    {
        $request_money = RequestMoney::find($request->id);

        //access check
        if($request_money->to_user_id != $request->user()->id) {
            Toastr::error(translate('unauthorized request'));
            return back();
        }

        if (strtolower($slug) == 'deny') {
            try {
                $request_money->type = 'denied';
                //$request_money->note = $request->note;
                $request_money->save();
            } catch (\Exception $e) {
                Toastr::error(translate('Something went wrong'));
                return back();
            }

            //send notification
            Helpers::send_transaction_notification($request_money->from_user_id, $request_money->amount, 'denied_money');
            Helpers::send_transaction_notification($request_money->to_user_id, $request_money->amount, 'denied_money');

            Toastr::success(translate('Successfully changed the status'));
            return back();

        } elseif (strtolower($slug) == 'approve') {

            //START TRANSACTION
            DB::beginTransaction();
            $data = [];
            $data['from_user_id'] = $request_money->to_user_id;     //$data['from_user_id'] ##payment perspective##     //$request_money->to_user_id ##request sending perspective##
            $data['to_user_id'] = $request_money->from_user_id;

            try {
                $sendmoney_charge =0;   //since agent transaction has no change
                //customer(sender) transaction
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'debit';
                $data['transaction_type'] = SEND_MONEY;
                $data['ref_trans_id'] = null;
                $data['amount'] = $request_money->amount + $sendmoney_charge;

                if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    Toastr::error(translate('Insufficient Balance'));
                    return back();
                }

                $customer_transaction = Helpers::make_transaction($data);

                //send notification
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

                if ($customer_transaction == null) {
                    throw new TransactionFailedException('Transaction from sender is failed');
                }

                //customer(receiver) transaction
                $data['user_id'] = $data['to_user_id'];
                $data['type'] = 'credit';
                $data['transaction_type'] = RECEIVED_MONEY;
                $data['ref_trans_id'] = $customer_transaction;
                $data['amount'] = $request_money->amount;
                $agent_transaction = Helpers::make_transaction($data);

                //send notification
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

                if ($agent_transaction == null) {
                    throw new TransactionFailedException('Transaction to receiver is failed');
                }

//                //admin transaction (admin_charge)
//                //$data['user_id'] = 1;
//                $data['type'] = 'credit';
//                $data['transaction_type'] = ADMIN_CHARGE;
//                $data['ref_trans_id'] = $customer_transaction;
//                $data['charge'] = $sendmoney_charge;
//                $data['amount'] = $data['charge'];
//                $admin_transaction = Helpers::make_transaction($data);
//                if ($admin_transaction == null) {
//                    throw new TransactionFailedException('Transaction is failed');
//                }

                //request money status update
                $request_money->type = 'approved';
                $request_money->save();

                DB::commit();

            } catch (TransactionFailedException $e) {
                DB::rollBack();
                //return response()->json(['message' => $e->getMessage()], 501);
                Toastr::error(translate('Status change failed'));
                return back();
            }


            Toastr::success(translate('Successfully changed the status'));
            return back();

        } else {
            Toastr::error(translate('Status change failed'));
            return back();
        }

    }
}
