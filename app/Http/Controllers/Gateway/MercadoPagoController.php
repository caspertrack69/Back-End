<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\SDK;

class MercadoPagoController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = Helpers::get_business_settings('mercadopago');
    }
    public function index(Request $request)
    {
        $data = $this->data;

        $amount = session('amount');
        $ref_id = null;

        return view('payment-view-marcedo-pogo', compact('data', 'amount', 'ref_id'));
    }
    public function make_payment(Request $request)
    {

        SDK::setAccessToken($this->data['access_token']);
        $payment = new Payment();
        $payment->transaction_amount = (float)$request['transactionAmount'];
        $payment->token = $request['token'];
        $payment->description = $request['description'];
        $payment->installments = (int)$request['installments'];
        $payment->payment_method_id = $request['paymentMethodId'];
        $payment->issuer_id = (int)$request['issuer'];

        $payer = new Payer();
        $payer->email = $request['payer']['email'];
        $payer->identification = array(
            "type" => $request['payer']['identification']['type'],
            "number" => $request['payer']['identification']['number']
        );
        $payment->payer = $payer;

        $payment->save();

        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );

        if($payment->error)
        {
            $response['error'] = $payment->error->message;
        }
        if($payment->status == 'approved')
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
                return back()->withErrors(['error' => 'Failed']);

            }
        }
        return response()->json($response);
    }

    public function get_test_user(Request $request)
    {
        // curl -X POST \
        // -H "Content-Type: application/json" \
        // -H 'Authorization: Bearer PROD_ACCESS_TOKEN' \
        // "https://api.mercadopago.com/users/test_user" \
        // -d '{"site_id":"MLA"}'

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/users/test_user");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->data['access_token']
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"site_id":"MLA"}');
        $response = curl_exec($curl);
        dd($response);

    }
}
