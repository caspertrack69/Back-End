<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\EMoney;
use App\Models\RequestMoney;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function send_money(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4',
            'phone' => 'required',
            'purpose' => '',
            'amount' => 'required|min:0|not_in:0',
        ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $receiver_phone = Helpers::filter_phone($request->phone);
        $user = User::where('phone', $receiver_phone)->first();
        //Receiver Check
        if (!isset($user)) {
            return response()->json(['message' => 'Receiver not found'], 403);
        }

        //kyc check
        if($user->is_kyc_verified != 1) {
            return response()->json(['message' => 'Receiver is not verified'], 403);
        }
        if($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => 'Complete your account verification'], 403);
        }

        //own number transaction check
        if ($request->user()->phone == $receiver_phone) {
            return response()->json(['message' => 'Transaction should not with own number'], 400);
        }

        //'if receiver is customer' check
        if($user->type != 2) {
            return response()->json(['message' => 'Receiver must be a user'], 400);
        }

        //PIN Check
        if (!Helpers::pin_check($request->user()->id, $request->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 403);
        }


        //START TRANSACTION
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = $request->user()->id;
        $data['to_user_id'] = Helpers::get_user_id($receiver_phone);

        try {
            $sendmoney_charge = Helpers::get_sendmoney_charge();
            //customer(sender) transaction
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'debit';
            $data['transaction_type'] = SEND_MONEY;
            $data['ref_trans_id'] = null;
            $data['amount'] = $request->amount + $sendmoney_charge;

            if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                return response()->json(['message' => 'Insufficient Balance'], 403);
            }

            $customer_transaction = Helpers::make_transaction($data);

            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            if ($customer_transaction == null) {
                throw new TransactionFailedException('Transaction from receiver is failed');
            }

            //customer(receiver) transaction
            $data['user_id'] = $data['to_user_id'];
            $data['type'] = 'credit';
            $data['transaction_type'] = RECEIVED_MONEY;
            $data['ref_trans_id'] = $customer_transaction;
            $data['note'] = $request->purpose;
            $data['amount'] = $request->amount;
            $agent_transaction = Helpers::make_transaction($data);

            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            if ($agent_transaction == null) {
                throw new TransactionFailedException('Transaction to sender is failed');
            }

            //admin transaction (admin_charge)
            //$data['user_id'] = 1;
            $data['type'] = 'credit';
            $data['transaction_type'] = ADMIN_CHARGE;
            $data['ref_trans_id'] = $customer_transaction;
            $data['charge'] = $sendmoney_charge;
            $data['amount'] = $data['charge'];
            $admin_transaction = Helpers::make_transaction($data);
            if ($admin_transaction == null) {
                throw new TransactionFailedException('Admin charge transaction is failed');
            }

            DB::commit();

        } catch (TransactionFailedException $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 501);
        }

        return response()->json([
            'message' => 'success',
            'transaction_id' => $customer_transaction
        ], 200);
    }

    public function cash_out(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4',
            'phone' => 'required',
            'amount' => 'required|min:0|not_in:0',
        ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $receiver_phone = Helpers::filter_phone($request->phone);
        $user = User::where('phone', $receiver_phone)->first();
        //Receiver Check
        if (!isset($user)) {
            return response()->json(['message' => 'Receiver not found'], 403);
        }

        //kyc check
        if($user->is_kyc_verified != 1) {
            return response()->json(['message' => 'Receiver is not verified'], 403);
        }
        if($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => 'Verify your account information'], 403);
        }

        //own number transaction check
        if ($request->user()->phone == $receiver_phone) {
            return response()->json(['message' => 'Transaction should not with own number'], 400);
        }

        //'if receiver is customer' check
        if($user->type != 1) {
            return response()->json(['message' => 'Receiver must be an agent'], 400);
        }

        //PIN Check
        if (!Helpers::pin_check($request->user()->id, $request->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 403);
        }

        //START TRANSACTION
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = $request->user()->id;
        $data['to_user_id'] = Helpers::get_user_id($receiver_phone);

        try {
            $cashout_charge = Helpers::get_cashout_charge($request->amount);
            //customer transaction
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'debit';
            $data['transaction_type'] = CASH_OUT;
            $data['ref_trans_id'] = null;
            $data['amount'] = $request->amount + $cashout_charge;

            if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                return response()->json(['message' => 'Insufficient Balance'], 403);
            }

            $customer_transaction = Helpers::make_transaction($data);

            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            if ($customer_transaction == null) {
                throw new TransactionFailedException('Transaction from receiver is failed');
            }

            //agent transaction
            $data['user_id'] = $data['to_user_id'];;
            $data['type'] = 'credit';
            $data['transaction_type'] = CASH_IN;
            $data['ref_trans_id'] = $customer_transaction;
            $data['charge'] = Helpers::get_agent_commission($cashout_charge);

            //agent transaction for amount
            $data['amount'] = $request->amount;
            $data['transaction_type'] = CASH_IN;
            $agent_transaction_for_amount = Helpers::make_transaction($data);

            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            //agent transaction for commission
            $data['amount'] = $data['charge'];
            $data['transaction_type'] = 'agent_commission';
            $agent_transaction_for_commission = Helpers::make_transaction($data);

            if ($agent_transaction_for_amount == null || $agent_transaction_for_commission == null) {
                throw new TransactionFailedException('Transaction to sender is failed');
            }

            //admin transaction (admin_charge)
            //$data['user_id'] = 1;
            $data['type'] = 'credit';
            $data['transaction_type'] = ADMIN_CHARGE;
            $data['ref_trans_id'] = $customer_transaction;
            $data['charge'] = $cashout_charge - $data['charge'];
            $data['amount'] = $data['charge'];
            $admin_transaction = Helpers::make_transaction($data);
            if ($admin_transaction == null) {
                throw new TransactionFailedException('Admin charge transaction is failed');
            }

            DB::commit();

        } catch (TransactionFailedException $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 501);
        }

        return response()->json([
            'message' => 'success',
            'transaction_id' => $customer_transaction
        ], 200);

    }

    public function request_money(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'amount' => 'required|min:0|not_in:0',
            'note' => '',
        ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $receiver_phone = Helpers::filter_phone($request->phone);
        $user = User::where('phone', $receiver_phone)->first();
        //Receiver Check
        if (!isset($user)) {
            return response()->json(['message' => 'Receiver not found'], 403);
        }

        //kyc check
        if($user->is_kyc_verified != 1) {
            return response()->json(['message' => 'Receiver is not verified'], 403);
        }
        if($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => 'Verify your account information'], 403);
        }

        //own number transaction check
        if ($request->user()->phone == $receiver_phone) {
            return response()->json(['message' => 'Transaction should not with own number'], 400);
        }

        //'if receiver is customer' check
        if($user->type !=  2) {
            return response()->json(['message' => 'Receiver must be a user'], 400);
        }

        try {
            $request_money = new RequestMoney();
            $request_money->from_user_id = $request->user()->id;
            $request_money->to_user_id = Helpers::get_user_id($receiver_phone);
            $request_money->type = 'pending';
            $request_money->amount = $request->amount;
            $request_money->note = $request->note;
            $request_money->save();

        } catch (Exception $e) {
            return response()->json(['message' => 'failed'], 502);
        }

        //send notification
        Helpers::send_transaction_notification($request_money->from_user_id, $request->amount, 'request_money');
        Helpers::send_transaction_notification($request_money->to_user_id, $request->amount, 'request_money');

        return response()->json(['message' => 'success'], 200);
    }

    public function request_money_status(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4',
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $request_money = RequestMoney::find($request->id);

        //kyc check
        if(User::find($request_money->to_user_id)->is_kyc_verified != 1) {
            return response()->json(['message' => 'Receiver is not verified'], 403);
        }
        if($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => 'Complete your account verification'], 403);
        }

        //access check
        if($request_money->to_user_id != $request->user()->id) {
            return response()->json(['message' => 'unauthorized request'], 403);
        }

        //PIN Check
        if (!Helpers::pin_check($request->user()->id, $request->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 403);
        }

        if (strtolower($slug) == 'deny') {
            try {
                $request_money->type = 'denied';
                $request_money->note = $request->note;
                $request_money->save();
            } catch (Exception $e) {
                return response()->json(['message' => 'failed'], 502);
            }

            //send notification
            Helpers::send_transaction_notification($request_money->from_user_id, $request->amount, 'denied_money');
            Helpers::send_transaction_notification($request_money->to_user_id, $request->amount, 'denied_money');

            return response()->json(['message' => 'success'], 200);

        } elseif (strtolower($slug) == 'approve') {

            //START TRANSACTION
            DB::beginTransaction();
            $data = [];
            $data['from_user_id'] = $request_money->to_user_id;     //$data['from_user_id'] ##payment perspective##     //$request_money->to_user_id ##request sending perspective##
            $data['to_user_id'] = $request_money->from_user_id;

            try {
                $sendmoney_charge = Helpers::get_sendmoney_charge();
                //customer(sender) transaction
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'debit';
                $data['transaction_type'] = SEND_MONEY;
                $data['ref_trans_id'] = null;
                $data['amount'] = $request_money->amount + $sendmoney_charge;

                if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    return response()->json(['message' => 'Insufficient Balance'], 403);
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

                //admin transaction (admin_charge)
                //$data['user_id'] = 1;
                $data['type'] = 'credit';
                $data['transaction_type'] = ADMIN_CHARGE;
                $data['ref_trans_id'] = $customer_transaction;
                $data['charge'] = $sendmoney_charge;
                $data['amount'] = $data['charge'];
                $admin_transaction = Helpers::make_transaction($data);
                if ($admin_transaction == null) {
                    throw new TransactionFailedException('Transaction is failed');
                }

                //request money status update
                $request_money->type = 'approved';
                $request_money->save();

                DB::commit();

            } catch (TransactionFailedException $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getMessage()], 501);
            }

            return response()->json([
                'message' => 'success',
                'transaction_id' => $customer_transaction
            ], 200);

        } else {
            return response()->json(['message' => 'Invalid request'], 403);
        }

    }

    public function add_money(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //kyc check
        if($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => 'Verify your account information'], 403);
        }

        $user_id = $request->user()->id;
        $amount = $request->amount;
        $link = route('payment-mobile', ['user_id' => $user_id, 'amount' => $amount]);
        return response()->json(['link' => $link], 200);
    }

    public function transaction_history(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $transactions = Transaction::where('user_id', $request->user()->id);

        $transactions->when(request('transaction_type') == CASH_IN, function ($q) {
            return $q->where('transaction_type', CASH_IN);
        });
        $transactions->when(request('transaction_type') == CASH_OUT, function ($q) {
            return $q->where('transaction_type', CASH_OUT);
        });
        $transactions->when(request('transaction_type') == SEND_MONEY, function ($q) {
            return $q->where('transaction_type', SEND_MONEY);
        });
        $transactions->when(request('transaction_type') == RECEIVED_MONEY, function ($q) {
            return $q->where('transaction_type', RECEIVED_MONEY);
        });
        $transactions->when(request('transaction_type') == ADD_MONEY, function ($q) {
            return $q->where('transaction_type', ADD_MONEY);
        });

        $transactions = $transactions
//            ->select('transaction_type', 'credit', 'debit', 'created_at', DB::raw("(debit + credit) as amount"))
//            ->select('transaction_type', 'credit', 'debit', 'created_at')
            ->customer()
            ->where('transaction_type', '!=', ADMIN_CHARGE)
            ->where('transaction_type', '!=', 'agent_commission')
            ->orderBy("created_at", 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $transactions = TransactionResource::collection($transactions);

        return [
            'total_size' => $transactions->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'transactions' => $transactions->items()
        ];
    }
}
