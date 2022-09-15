<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    public function payWithRazorpay()
    {
        return view('razor-pay');
    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        $user_id = $request->user_id;
        $amount = $request->amount;

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
//                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
//                $id = 111; //hard coded
//                $tr_ref = $input['razorpay_payment_id'];


                //add money charge
                $add_money_charge = \App\CentralLogics\Helpers::get_business_settings('addmoney_charge_percent');
                if(isset($add_money_charge) && $add_money_charge > 0) {
                    $add_money_charge = ($amount * $add_money_charge)/100;
                } else {
                    $add_money_charge = 0;
                }

                //transaction
                DB::beginTransaction();
                $data = [];
                $data['from_user_id'] = Helpers::get_admin_id(); //since admin
                $data['to_user_id'] = $user_id;

                try {
                    //customer transaction
                    $data['user_id'] = $data['to_user_id'];
                    $data['type'] = 'credit';
                    $data['transaction_type'] = ADD_MONEY;
                    $data['ref_trans_id'] = null;
                    $data['amount'] = $amount;

                    $customer_transaction = Helpers::make_transaction($data);
                    if ($customer_transaction != null) {
                        //admin transaction
                        $data['user_id'] = $data['from_user_id'];
                        $data['type'] = 'debit';
                        $data['transaction_type'] = SEND_MONEY;
                        $data['ref_trans_id'] = $customer_transaction;
                        $data['amount'] = $amount + $add_money_charge;
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
                            $data['user_id'] = $user_id;
                            $data['amount'] = $amount;
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
                            $data['user_id'] = $user_id;
                            $data['amount'] = $amount;
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


            } catch (\Exception $e) {
                //fund record for failed
                try {
                    $data = [];
                    $data['user_id'] = $user_id;
                    $data['amount'] = $amount;
                    $data['payment_method'] = 'paypal';
                    $data['status'] = 'razorpay';
                    Helpers::fund_add($data);

                } catch (\Exception $exception) {
                    Toastr::error('Something went wrong!');
                    return back();
                }
                return \redirect()->route('payment-fail');
            }
        }

        return \redirect()->route('payment-success');
    }

}
