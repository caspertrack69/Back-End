<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BkashPaymentController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    public function __construct()
    {
        $config=\App\CentralLogics\Helpers::get_business_settings('bkash');
        // You can import it from your Database
        $bkash_app_key = $config['api_key']; // bKash Merchant API APP KEY
        $bkash_app_secret = $config['api_secret']; // bKash Merchant API APP SECRET
        $bkash_username = $config['username']; // bKash Merchant API USERNAME
        $bkash_password = $config['password']; // bKash Merchant API PASSWORD
        $bkash_base_url = (env('APP_MODE') == 'live') ? 'https://checkout.pay.bka.sh/v1.2.0-beta' : 'https://checkout.sandbox.bka.sh/v1.2.0-beta';

        $this->app_key = $bkash_app_key;
        $this->app_secret = $bkash_app_secret;
        $this->username = $bkash_username;
        $this->password = $bkash_password;
        $this->base_url = $bkash_base_url;
    }

    public function getToken()
    {
        session()->forget('bkash_token');

        $post_token = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );

        $url = curl_init("$this->base_url/checkout/token/grant");
        $post_token = json_encode($post_token);
        $header = array(
            'Content-Type:application/json',
            "password:$this->password",
            "username:$this->username"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        session()->put('bkash_token', $response['id_token']);

        return response()->json(['success', true]);
    }

    public function createPayment(Request $request)
    {
        $token = session()->get('bkash_token');

        $request['intent'] = 'sale';
        $request['currency'] = 'BDT';
        $request['merchantInvoiceNumber'] = rand();

        $url = curl_init("$this->base_url/checkout/payment/create");
        $request_data_json = json_encode($request->all());
        $header = array(
            'Content-Type:application/json',
            "authorization: $token",
            "x-app-key: $this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function executePayment(Request $request)
    {
        $token = session()->get('bkash_token');

        $paymentID = $request->paymentID;
        $url = curl_init("$this->base_url/checkout/payment/execute/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function queryPayment(Request $request)
    {
        $token = session()->get('bkash_token');
        $paymentID = $request->payment_info['payment_id'];

        $url = curl_init("$this->base_url/checkout/payment/query/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function bkashSuccess(Request $request)
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
}
