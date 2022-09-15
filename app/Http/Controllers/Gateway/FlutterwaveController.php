<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\EMoney;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

//        $order = Order::with(['details'])->where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();

        $user_data = User::find(session('user_id'));
        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => session('amount'), //hard coded
            'email' => $user_data['email'] ?? EXAMPLE_MAIL,
            'tx_ref' => $reference,
            'currency' => Helpers::currency_code(),
            'redirect_url' => route('flutterwave_callback'),
            'customer' => [
                'email' => $user_data['email'] ?? EXAMPLE_MAIL,
                "phone_number" => $user_data['phone'],
                "name" => $user_data['f_name']??'' . ' ' . $user_data['l_name']??'',
            ],

            "customizations" => [
                "title" => BusinessSetting::where(['key'=>'business_name'])->first()->value??'6CASH',
                "description" => null,
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            //return to callback


            //payment-fail if no callback

            return \redirect()->route('payment-fail');
        }
        return redirect($payment['data']['link']);

    }

    public function callback()
    {
        $status = request()->status;
//        $order = Order::with(['details'])->where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        //if payment is successful
        if ($status == 'successful') {
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            //transaction
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
         elseif ($status ==  'cancelled'){
             //Put desired action/code after transaction has been cancelled here
             //fund record for failed
             try {
                 $data = [];
                 $data['user_id'] = session('user_id');
                 $data['amount'] = session('amount');
                 $data['payment_method'] = 'flutterwave';
                 $data['status'] = 'cancel';
                 Helpers::fund_add($data);

             } catch (\Exception $exception) {
                 Toastr::error('Something went wrong!');
                 return back();
             }
             return \redirect()->route('payment-fail');
         }
        else{
            //fund record for failed
            try {
                $data = [];
                $data['user_id'] = session('user_id');
                $data['amount'] = session('amount');
                $data['payment_method'] = 'flutterwave';
                $data['status'] = 'failed';
                Helpers::fund_add($data);

            } catch (\Exception $exception) {
                Toastr::error('Something went wrong!');
                return back();
            }
            return \redirect()->route('payment-fail');

        }
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here

    }
}
