<?php

namespace App\Http\Controllers\Gateway;

//use App\CentralLogics\CartManager;
use App\CentralLogics\Helpers;
//use App\CentralLogics\OrderManager;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CentralLogics\translate;

class PaymobController extends Controller
{
    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    protected function GETcURL($url)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    public function credit()
    {
        $currency_code = Currency::where(['currency_code' => 'EGP'])->first();
        if (isset($currency_code) == false) {
            Toastr::error(translate('paymob_supports_EGP_currency'));
            return back()->withErrors(['error' => 'Failed']);
        }

        $config = Helpers::get_business_settings('paymob');
        try {
            $token = $this->getToken();
            $order = $this->createOrder($token);
            $paymentToken = $this->getPaymentToken($order, $token);
        }catch (\Exception $exception){
            Toastr::error(translate('country_permission_denied_or_misconfiguration'));
            return back()->withErrors(['error' => 'Failed']);
        }
        return \Redirect::away('https://portal.weaccept.co/api/acceptance/iframes/' . $config['iframe_id'] . '?payment_token=' . $paymentToken);
    }

    public function getToken()
    {
        $config = Helpers::get_business_settings('paymob');
        $response = $this->cURL(
            'https://accept.paymobsolutions.com/api/auth/tokens',
            ['api_key' => $config['api_key']]
        );

        return $response->token;
    }

    public function createOrder($token)
    {
        $value = session('amount');

        $items = []; //items will be here

        $data = [
            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" => round($value,2) * 100,
            "currency" => "EGP",
            "items" => $items,

        ];
        $response = $this->cURL(
            'https://accept.paymob.com/api/ecommerce/orders',
            $data
        );

        return $response;
    }

    public function getPaymentToken($order, $token)
    {
        $value = session('amount');

        $config = Helpers::get_business_settings('paymob');
        $billingData = [
            "apartment" => "not given",
            "email" => "not given",
            "floor" => "not given",
            "first_name" => "not given",
            "street" => "not given",
            "building" => "not given",
            "phone_number" => "not given",
            "shipping_method" => "PKG",
            "postal_code" => "not given",
            "city" => "not given",
            "country" => "not given",
            "last_name" => "not given",
            "state" => "not given",
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => round($value,2) * 100,
            "expiration" => 3600,
            "order_id" => null,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $config['integration_id']
        ];

        $response = $this->cURL(
            'https://accept.paymob.com/api/acceptance/payment_keys',
            $data
        );

        return $response->token;
    }

    public function callback(Request $request)
    {
        $config = Helpers::get_business_settings('paymob');
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = $config['hmac'];
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ($hased == $hmac) {
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
                return back()->withErrors(['error' => 'Failed']);

            }

        }

        //fund record for failed
        try {
            $data = [];
            $data['user_id'] = session('user_id');
            $data['amount'] = session('amount');
            $data['payment_method'] = 'paymob';
            $data['status'] = 'failed';
            Helpers::fund_add($data);

        } catch (\Exception $exception) {}
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
