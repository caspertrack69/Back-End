<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalPaymentController extends Controller
{
    public function __construct()
    {
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $tr_ref = Str::random(6) . '-' . rand(1, 1000);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $pay_amount = session('amount');
        $user_id = session('user_id');
        $customer = User::find($user_id);

        $items_array = [];
        $item = new Item();
        $item->setName($customer->f_name)
            ->setCurrency(Helpers::currency_code())
            ->setQuantity(1)
            ->setPrice($pay_amount);
        array_push($items_array, $item);

        $item_list = new ItemList();
        $item_list->setItems($items_array);

        $amount = new Amount();
        $amount->setCurrency(Helpers::currency_code())
            ->setTotal($pay_amount);

        \session()->put('transaction_reference', $tr_ref);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($tr_ref);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('paypal-status'))
            ->setCancelUrl(URL::route('payment-fail'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }

        } catch (\Exception $ex) {
            Toastr::error('Your currency is not supported by PAYPAL.');
            return back()->withErrors(['error' => 'Failed']);
        }

        Session::put('error', 'Configure your paypal account.');
        return back()->withErrors(['error' => 'Failed']);
    }

    public function getPaymentStatus(Request $request)
    {
        $payment_id = Session::get('paypal_payment_id');
        if (empty($request['PayerID']) || empty($request['token'])) {
            Session::put('error', 'Payment failed');
            return Redirect::back();
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
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
            $data['payment_method'] = 'paypal';
            $data['status'] = 'failed';
            Helpers::fund_add($data);

        } catch (\Exception $exception) {
            Toastr::error('Something went wrong!');
            return back()->withErrors(['error' => 'Failed']);
        }
        return \redirect()->route('payment-fail');
    }
}
