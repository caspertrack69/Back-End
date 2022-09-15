<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\EMoney;
use App\Models\Fund;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SslCommerzPaymentController extends Controller
{

    public function index(Request $request)
    {
        $data = [];
        $data['user_id'] = session('user_id');
        $data['amount'] = session('amount');
        $user = User::find($data['user_id']);
        $data['name'] = $user['f_name'];
        $data['phone'] = $user['phone'];
        $data['email'] = $user['email'] ?? EXAMPLE_MAIL;

        session()->put('user_id', $data['user_id']);
        session()->put('amount', $data['amount']);

        $tr_ref = Str::random(6) . '-' . rand(1, 1000);

        $post_data = array();
        $post_data['total_amount'] = $data['amount'];
        $post_data['currency'] = Helpers::currency_code();
        $post_data['tran_id'] = $tr_ref;

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $data['name'];
        $post_data['cus_email'] = $data['email'];
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $data['phone'];
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Shipping";
        $post_data['ship_add1'] = "address 1";
        $post_data['ship_add2'] = "address 2";
        $post_data['ship_city'] = "City";
        $post_data['ship_state'] = "State";
        $post_data['ship_postcode'] = "ZIP";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Country";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        //fund add
        try {
            $data = [];
            $data['user_id'] = session('user_id');
            $data['amount'] = session('amount');
            $data['payment_method'] = 'ssl_commerz';
            $data['tran_id'] = $tr_ref;
            Helpers::fund_add($data);

        } catch (\Exception $exception) {
            Toastr::error('Something went wrong!');
            return back()->withErrors(['error' => 'Failed']);
        }

        try {
            $sslc = new SslCommerzNotification();
            $payment_options = $sslc->makePayment($post_data, 'hosted');
            if (!is_array($payment_options)) {
                Toastr::error('Your currency is not supported by SSLCOMMERZ.');
                return back()->withErrors(['error' => 'Failed']);
            }
        } catch (\Exception $exception) {
            Toastr::error('Misconfiguration or data is missing!');
            return back()->withErrors(['error' => 'Failed']);
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

        if ($validation == TRUE) {
            //fund status update
            $fund_data = Helpers::fund_update($tran_id, 'success');

            //add money charge
            $add_money_charge = \App\CentralLogics\Helpers::get_business_settings('addmoney_charge_percent');
            if(isset($add_money_charge) && $add_money_charge > 0) {
                $add_money_charge = ($fund_data['amount'] * $add_money_charge)/100;
            } else {
                $add_money_charge = 0;
            }

            if($fund_data === null) {
                return \redirect()->route('payment-fail');
            }

            //START TRANSACTION
            DB::beginTransaction();
            $data = [];
            $data['from_user_id'] = helpers::get_admin_id(); //since admin
            $data['to_user_id'] = $fund_data['user_id'];

            //admin transaction
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'debit';
            $data['transaction_type'] = SEND_MONEY;
            $data['ref_trans_id'] = null;
            $data['amount'] = $fund_data['amount']+$add_money_charge;
            if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                DB::rollBack();
                Helpers::fund_update($tran_id, 'failed');
                return \redirect()->route('payment-fail');
            }
            $admin_transaction = Helpers::make_transaction($data);
            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            //admin charge transaction
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'credit';
            $data['transaction_type'] = ADMIN_CHARGE;
            $data['ref_trans_id'] = null;
            $data['amount'] = $add_money_charge;
            $data['charge'] = $add_money_charge;

            $admin_transaction_for_charge = Helpers::make_transaction($data);
            //send notification
            Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            if ($admin_transaction != null && $admin_transaction_for_charge != null) {
                //customer transaction
                $data['user_id'] = $data['to_user_id'];
                $data['type'] = 'credit';
                $data['transaction_type'] = ADD_MONEY;
                $data['ref_trans_id'] = $admin_transaction;
                $data['amount'] = $fund_data['amount'];
                $customer_transaction = Helpers::make_transaction($data);
                //send notification
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

            }

            if($admin_transaction == null || $customer_transaction == null) {
                DB::rollBack();
                return \redirect()->route('payment-fail');
            } else {
                DB::commit();
                return \redirect()->route('payment-success');
            }

        } else {
            return \redirect()->route('payment-fail');
        }
    }

    public function fail(Request $request)
    {
        //fund status update
        $fund_update = Helpers::fund_update($request->tran_id, 'failed');
        if($fund_update === null) {
            Toastr::error('Something went wrong!');
            return back()->withErrors(['error' => 'Failed']);
        }

        return \redirect()->route('payment-fail');
    }

    public function cancel(Request $request)
    {
        //fund status update
        $fund_update = Helpers::fund_update($request->tran_id, 'canceled');
        if ($fund_update === null) {
            Toastr::error('Something went wrong!');
            return back()->withErrors(['error' => 'Failed']);
        }

        return \redirect()->route('payment-cancel');
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');
            //
        } else {
            echo "Invalid Data";
        }
    }
}
