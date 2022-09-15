<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function payment_process_3d()
    {
        $tran = Str::random(6) . '-' . rand(1, 1000);
        session()->put('transaction_ref', $tran);

        $config = Helpers::get_business_settings('stripe');
        Stripe::setApiKey($config['api_key']);
        header('Content-Type: application/json');
        $currency_code = Helpers::get_business_settings('currency');

        $YOUR_DOMAIN = url('/');

        $currencies_not_supported_cents = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
        $amount = session('amount');
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency_code??'usd',
                    'unit_amount' => in_array($currency_code, $currencies_not_supported_cents) ? (int)$amount : ($amount * 100),
                    'product_data' => [
                        'name' => Helpers::get_business_settings('business_name')??'No Title',
                        'images' => [asset('storage/app/public/system') . '/' . Helpers::get_business_settings('logo')??'']
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/pay-stripe/success',
            'cancel_url' => url()->previous(),
        ]);
        return response()->json(['id' => $checkout_session->id]);
    }

    public function success()
    {
        //add money charge
        $add_money_charge = \App\CentralLogics\Helpers::get_business_settings('addmoney_charge_percent');
        if(isset($add_money_charge) && $add_money_charge > 0) {
            $add_money_charge = (session('amount') * $add_money_charge)/100;
        } else {
            $add_money_charge = 0;
        }

        //transaction
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = Helpers::get_admin_id(); //since admin
        $data['to_user_id'] = session('user_id');

        try {
            //customer transaction
            $data['user_id'] = $data['to_user_id'];
            $data['type'] = 'credit';
            $data['transaction_type'] = ADD_MONEY;
            $data['ref_trans_id'] = null;
            $data['amount'] = session('amount');

            $customer_transaction = Helpers::make_transaction($data);
            if ($customer_transaction != null) {
                //admin transaction
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'debit';
                $data['transaction_type'] = SEND_MONEY;
                $data['ref_trans_id'] = $customer_transaction;
                $data['amount'] = session('amount') + $add_money_charge;
                if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    DB::rollBack();
                    return \redirect()->route('payment-fail');
                }
                $admin_transaction = Helpers::make_transaction($data);
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);


                //admin charge transaction
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'credit';
                $data['transaction_type'] = ADMIN_CHARGE;
                $data['ref_trans_id'] = null;
                $data['amount'] = $add_money_charge;
                $data['charge'] = $add_money_charge;
                if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    DB::rollBack();
                    return \redirect()->route('payment-fail');
                }
                $admin_transaction_for_charge = Helpers::make_transaction($data);
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            }

            if ($admin_transaction == null || $admin_transaction_for_charge == null) {
                //fund record for failed
                try {
                    $data = [];
                    $data['user_id'] = session('user_id');
                    $data['amount'] = session('amount');
                    $data['payment_method'] = 'stripe';
                    $data['status'] = 'failed';
                    Helpers::fund_add($data);

                } catch (\Exception $exception) {
                    throw new TransactionFailedException('Fund record failed');
                }
                DB::rollBack();
                return \redirect()->route('payment-fail');

            } else {
                //fund record for success
                try {
                    $data = [];
                    $data['user_id'] = session('user_id');
                    $data['amount'] = session('amount');
                    $data['payment_method'] = 'stripe';
                    $data['status'] = 'success';
                    Helpers::fund_add($data);

                } catch (\Exception $exception) {
                    throw new TransactionFailedException('Fund record failed');
                }
                DB::commit();
                return \redirect()->route('payment-success');
            }


        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error('Something went wrong!');
            return back();

        }
    }

    public function fail()
    {
        //fund record for failed
        try {
            $data = [];
            $data['user_id'] = session('user_id');
            $data['amount'] = session('amount');
            $data['payment_method'] = 'stripe';
            $data['status'] = 'failed';
            Helpers::fund_add($data);

        } catch (\Exception $exception) {
            Toastr::error('Something went wrong!');
            return back();
        }
        return \redirect()->route('payment-fail');
    }
}
