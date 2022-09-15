<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\LinkedWebsite;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessSettingsController extends Controller
{
    public function business_index()
    {
        return view('admin-views.business-settings.business-index');
    }

    public function business_setup(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('update_option_is_disable_for_demo'));
            return back();
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['restaurant_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'agent_commission_percent'], [
            'value' => $request['agent_commission_percent']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'cashout_charge_percent'], [
            'value' => $request['cashout_charge_percent']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'addmoney_charge_percent'], [
            'value' => $request['addmoney_charge_percent']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'sendmoney_charge_flat'], [
            'value' => $request['sendmoney_charge_flat']
        ]);

        $curr_logo = BusinessSetting::where(['key' => 'logo'])->first() ?? '';
        if ($request->has('logo')) {
            $image_name = Helpers::update('business/', $curr_logo->value ?? '', 'png', $request->file('logo'));
        } else {
            $image_name = $curr_logo['value'] ?? '';
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email'], [
            'value' => $request['email']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'inactive_auth_minute'], [
            'value' => $request['inactive_auth_minute']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'two_factor'], [
            'value' => $request['two_factor']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'refer_commission'], [
            'value' => $request['refer_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        Toastr::success(translate('successfully_updated_to_changes_restart_the_app'));
        return back();
    }

    public function payment_index()
    {
        return view('admin-views.business-settings.payment-index');
    }

    public function payment_update(Request $request, $name)
    {

        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'cash_on_delivery'])->update([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = BusinessSetting::where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'digital_payment'])->update([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'ssl_commerz_payment'])->update([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'razor_pay'])->update([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('key', 'paypal')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paypal'])->update([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('key', 'stripe')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'stripe'])->update([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'secret_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'senang_pay'])->update([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'secret_key' => $request['secret_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('key', 'paystack')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paystack'])->update([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } else if ($name == 'internal_point') {
            $payment = BusinessSetting::where('key', 'internal_point')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'internal_point'])->update([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } else if ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ])
            ]);
        } else if ($name == 'paymob') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paymob'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac']
                ])
            ]);
        } else if ($name == 'flutterwave') {
            DB::table('business_settings')->updateOrInsert(['key' => 'flutterwave'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'secret_key' => $request['secret_key'],
                    'hash' => $request['hash']
                ])
            ]);
        } else if ($name == 'mercadopago') {
            DB::table('business_settings')->updateOrInsert(['key' => 'mercadopago'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'access_token' => $request['access_token']
                ])
            ]);
        }

        Toastr::success(translate('payment settings updated!'));
        return back();
    }

    public function fcm_index()
    {
        return view('admin-views.business-settings.fcm-index');
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        Toastr::success(translate('settings_updated'));
        return back();
    }

    public function update_fcm_messages(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'money_transfer_message'], [
            'value' => json_encode([
                'status' => $request['money_transfer_status'] == 1 ? 1 : 0,
                'message' => $request['money_transfer_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => CASH_IN], [
            'value' => json_encode([
                'status' => $request['cash_in_status'] == 1 ? 1 : 0,
                'message' => $request['cash_in_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => CASH_OUT], [
            'value' => json_encode([
                'status' => $request['cash_out_status'] == 1 ? 1 : 0,
                'message' => $request['cash_out_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => SEND_MONEY], [
            'value' => json_encode([
                'status' => $request['send_money_status'] == 1 ? 1 : 0,
                'message' => $request['send_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'request_money'], [
            'value' => json_encode([
                'status' => $request['request_money_status'] == 1 ? 1 : 0,
                'message' => $request['request_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'denied_money'], [
            'value' => json_encode([
                'status' => $request['denied_money_status'] == 1 ? 1 : 0,
                'message' => $request['denied_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'approved_money'], [
            'value' => json_encode([
                'status' => $request['approved_money_status'] == 1 ? 1 : 0,
                'message' => $request['approved_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => ADD_MONEY], [
            'value' => json_encode([
                'status' => $request['add_money_status'] == 1 ? 1 : 0,
                'message' => $request['add_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => RECEIVED_MONEY], [
            'value' => json_encode([
                'status' => $request['received_money_status'] == 1 ? 1 : 0,
                'message' => $request['received_money_message']
            ])
        ]);

        Toastr::success(translate('message_updated'));
        return back();
    }

    public function terms_and_conditions()
    {
        $tnc = BusinessSetting::where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            BusinessSetting::insert([
                'key' => 'terms_and_conditions',
                'value' => '',
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        BusinessSetting::where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc,
        ]);

        Toastr::success(translate('Terms and Conditions updated!'));
        return back();
    }

    public function privacy_policy()
    {
        $data = BusinessSetting::where(['key' => 'privacy_policy'])->first();
        if ($data == false) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    public function privacy_policy_update(Request $request)
    {
        BusinessSetting::where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        Toastr::success(translate('Privacy policy updated!'));
        return back();
    }

    public function about_us()
    {
        $data = BusinessSetting::where(['key' => 'about_us'])->first();
        if ($data == false) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.about-us', compact('data'));
    }

    public function about_us_update(Request $request)
    {
        BusinessSetting::where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);

        Toastr::success(translate('About us updated!'));
        return back();
    }

    //linked website
    public function linked_website()
    {
        $linked_websites = LinkedWebsite::latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.business-settings.linked-website', compact('linked_websites'));
    }

    public function linked_website_add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'image' => 'required',
        ]);

        $linked_websites = new LinkedWebsite();
        $linked_websites->name = $request->name;
        $linked_websites->url = $request->url;
        $linked_websites->status = 1;
        $linked_websites->image = Helpers::upload('website/', 'png', $request->file('image'));
        $linked_websites->save();

        Toastr::success(translate('Added Successfully!'));
        return back();
    }

    public function linked_website_edit($id)
    {
        $linked_website = LinkedWebsite::find($id);
        return view('admin-views.business-settings.linked-website-edit', compact('linked_website'));
    }

    public function linked_website_update(Request $request)
    {
        $linked_websites = LinkedWebsite::find($request->id);
        $linked_websites->name = $request->name;
        $linked_websites->url = $request->url;
        $linked_websites->status = 1;
        $linked_websites->image = $request->has('image') ? Helpers::upload('website/', 'png', $request->file('image')) : $linked_websites->image;
        $linked_websites->save();

        Toastr::success(translate('Updated Successfully!'));
        return back();
    }

    PUBLIC FUNCTION linked_website_status($id)
    {
        $linked_websites = LinkedWebsite::find($id);
        $linked_websites->status = !$linked_websites->status;
        $linked_websites->save();

        Toastr::success(translate('Status Updated Successfully!'));
        return back();
    }

    public function linked_website_delete(Request $request)
    {
        $linked_website = LinkedWebsite::find($request->id);
        if (Storage::disk('public')->exists('banner/' . $linked_website['image'])) {
            Storage::disk('public')->delete('banner/' . $linked_website['image']);
        }
        $linked_website->delete();

        Toastr::success(translate('Website removed!'));
        return back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        Toastr::success('Updated Successfully');
        return back();
    }

    //app settings
    public function app_settings(Request $request)
    {
        return view('admin-views.business-settings.app-setting-index');
    }

    public function app_setting_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'app_theme'], [
            'value' => $request['theme']
        ]);

        Toastr::success('App theme Updated Successfully');
        return back();
    }


}
