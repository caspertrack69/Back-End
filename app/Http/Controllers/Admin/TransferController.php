<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\Transfer;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
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

            $transfers = Transfer::where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('sender', $users)
                        ->orWhereIn('receiver', $users)
                        ->orWhere('unique_id', 'like', "%{$value}%")
                        ->orWhere('receiver_type', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $transfers = new Transfer();
        }

        $unused_balance = EMoney::with('user')->whereHas('user', function ($q) {
            $q->where('type', '=', 0);
        })->sum('current_balance');

        $transfers = $transfers->orderBy('id', 'desc')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.transfer.index', compact('transfers', 'search', 'unused_balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required',
            'receiver_type' => '',
            'amount' => 'required|min:0|not_in:0',
        ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]);

        //START TRANSACTION
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = Helpers::get_admin_id();
        $data['to_user_id'] = $request->to_user_id;

        try {
            //customer transaction
            $data['user_id'] = $request->to_user_id;
            $data['type'] = 'credit';
            $data['transaction_type'] = CASH_IN;
            $data['ref_trans_id'] = null;
            $data['amount'] = $request->amount;
            $customer_transaction = Helpers::make_transaction($data);
            if ($customer_transaction != null) {
                //admin transaction
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'debit';
                $data['transaction_type'] = CASH_OUT;
                $data['ref_trans_id'] = $customer_transaction;
                $data['amount'] = $request->amount;
                if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    throw new TransactionFailedException();
                }
                $admin_transaction = Helpers::make_transaction($data);
            }

            //record transfer
            if ($admin_transaction != null) {
                try {
                    DB::transaction(function () use ($request) {
                        $transfer = new Transfer();
                        $transfer->sender = Helpers::get_admin_id();
                        $transfer->receiver = $request->to_user_id;
                        $transfer->receiver_type = User::find($request->to_user_id)->type ?? '';
                        $transfer->amount = $request->amount;
                        $transfer->save();

                        $transfer->find($transfer->id);
                        $transfer->unique_id = $transfer->id . mt_rand(111111, 9999999999);
                        $transfer->save();
                    });

                } catch (TransactionFailedException $e) {
                    throw new TransactionFailedException();
                }

            } else {
                throw new TransactionFailedException();
            }

            DB::commit();

        }catch (TransactionFailedException $e) {
            DB::rollBack();
            Toastr::error(translate('Failed!'));
            return back();
        }

        //push notification
        $fcm_token = User::find($data['to_user_id'])->fcm_token;
        $value = Helpers::order_status_update_message('money_transfer_message');
        try {
            if ($value) {
                $data = [
                    'title' => translate('Transaction'),
                    'description' => $value,
                    'image' => '',
                    'order_id'=>'',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
            Toastr::warning(translate('Push notification failed for Customer!'));
        }

        Toastr::success(translate('Transferred Successfully!'));
        return back();
    }

    public function get_user(Request $request)
    {
        $key = explode(' ', $request['q']);
        $receiver_type = $request['receiver_type'];
        $data = DB::table('users')
            ->where('type', $receiver_type)
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            })
//            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->limit(8)
            ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);

        $data[] = (object)['id' => false, 'text' => translate('Choose')];

        return response()->json($data);
    }
}
