<?php

namespace App\CentralLogics;

use App\Exceptions\TransactionFailedException;
use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\EMoney;
use App\Models\Fund;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravelpkg\Laravelchk\Http\Controllers\LaravelchkController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class helpers
{
    public static function send_push_notif_to_device($fcm_token, $data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = self::get_business_settings('push_notification_key');

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "mutable-content": "true",
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "is_read": 0
              },
             "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/' . $data['receiver'] . '",
            "mutable-content": "true",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_customers($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/notify_customers",
            "mutable-content": "true",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_agents($data)
    {
        /*https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send*/
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $key . "",
            "content-type: application/json"
        );

        $image = asset('storage/app/public/notification') . '/' . $data['image'];
        $postdata = '{
            "to" : "/topics/notify_agents,
            "mutable-content": "true",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $image . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "default"
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function order_status_update_message($status)
    {
        if ($status == 'money_transfer_message') {
            $data = self::get_business_settings('money_transfer_message');

        } elseif ($status == CASH_IN) {
            $data = self::get_business_settings(CASH_IN);

        } elseif ($status == CASH_OUT) {
            $data = self::get_business_settings(CASH_OUT);

        }  elseif ($status == SEND_MONEY) {
            $data = self::get_business_settings(SEND_MONEY);

        }  elseif ($status == 'request_money') {
            $data = self::get_business_settings('request_money');

        }  elseif ($status == 'denied_money') {
            $data = self::get_business_settings('denied_money');

        }  elseif ($status == 'approved_money') {
            $data = self::get_business_settings('approved_money');

        } elseif ($status == ADD_MONEY) {
            $data = self::get_business_settings(ADD_MONEY);

        } elseif ($status == RECEIVED_MONEY) {
            $data = self::get_business_settings(RECEIVED_MONEY);

        } else {
            $data['status'] = 0;
            $data['message'] = "";
        }

        if ($data == null || (array_key_exists('status', $data) && $data['status'] == 0)) {
            return 0;
        }
        return $data['message'];
    }

    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image == null) {
            return $old_image;
        }
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function response_formatter($constant, $content = null, $errors = []): array
    {
        $constant = (array)$constant;
        $constant['content'] = $content;
        $constant['errors'] = $errors;
        return $constant;
    }

    public static function file_uploader(string $dir, string $format, $image = null, $old_image = null)
    {
        if ($image == null) return $old_image ?? 'def.png';

        if (isset($old_image)) Storage::disk('public')->delete($dir . $old_image);

        $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
        Storage::disk('public')->put($dir . $imageName, file_get_contents($image));

        return $imageName;
    }

    public static function currency_code()
    {
        $currency_code = BusinessSetting::where(['key' => 'currency'])->first()->value??'USD';
        return $currency_code;
    }

    public static function currency_symbol()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol ?? '$';
        return $currency_symbol;
    }

    public static function set_symbol($amount)
    {
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = self::currency_symbol() . '' . number_format($amount, 2);
        } else {
            $string = number_format($amount, 2) . '' . self::currency_symbol();
        }
        return $string;
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $data = \App\Models\BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function pagination_limit()
    {
        $limit = self::get_business_settings('pagination_limit');
        return isset($limit) && $limit > 0 ? $limit : 25;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }

    public static function pin_check($user_id, $pin)
    {
        $user = User::find($user_id);
        if (Hash::check($pin, $user->password)) {
            return true;
        }else{
            return false;
        }
    }

    public static function get_qrcode($data)
    {
        $qrcode = QrCode::size(70)->generate(json_encode([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'type' => $data['type'] != 0 ? ($data['type'] == 1 ? 'agent' : 'customer') : null,
            'image' => $data['image'] ?? ''
        ]));
        return $qrcode;
    }

    public static function get_qrcode_by_phone($phone)
    {
        $user = User::where('phone', $phone)->first();
        $qrcode = QrCode::size(70)->generate(json_encode([
            'name' => $user['f_name'] . ' ' . $user['l_name'],
            'phone' => $user['phone'],
            'type' => $user['type'] != 0 ? ($user['type'] == 1 ? 'agent' : 'customer') : null,
            'image' => $user['image'] ?? ''
        ]));
        return $qrcode;

    }

    public static function filter_phone($phone) {
        $phone = str_replace([' ', '-'], '', $phone);
        return $phone;
    }

    public static function get_language_name($key)
    {
        $values = Helpers::get_business_settings('language');
        foreach ($values as $value) {
            if ($value['code'] == $key) {
                $key = $value['name'];
            }
        }

        return $key;
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('key', 'language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function get_cashout_charge($amount)
    {
        if ($amount <= 0) return $amount;
        $charge_in_percent = (float)self::get_business_settings('cashout_charge_percent');
        $charge = ((float)$amount * $charge_in_percent) / 100;
        return $charge;
    }

    public static function get_sendmoney_charge()
    {
        $sendmoney_charge = (float)self::get_business_settings('sendmoney_charge_flat');
        return $sendmoney_charge;
    }

    public static function get_agent_commission($amount)
    {
        if ($amount <= 0) return $amount;
        $commission_in_percent = (float)(self::get_business_settings('agent_commission_percent') ?? 1);
        $commission = ((float)$amount * $commission_in_percent) / 100;
        return $commission;
    }

    public static function get_user_info($user_id)
    {
        $user = User::find($user_id);
        if (isset($user)) {
            return $user;
        }
        return null;
    }

    public static function get_user_id($phone)
    {
        $user_id = User::where('phone', $phone)->first()->id;
        return $user_id;
    }

    public static function get_currency_symbol()
    {
        $currency_symbol = Currency::get()->first();

        if(isset($currency_symbol)) {
            return $currency_symbol->currency_symbol;
        } else {
            return null;
        }
    }

    public static function fund_update($tran_id, $status)
    {
        try {
            $fund = Fund::where('tran_id', $tran_id)->first();
            $fund->status = $status;
            $fund->save();

            return [
                'user_id' => $fund->user_id,
                'amount' => $fund->amount
            ];

        } catch (Exception $e) {
            return null;
        }
    }

    public static function fund_add($data)
    {
        $user_id = (integer)$data['user_id'];
        $amount = (float)$data['amount'];
        $payment_method = (string)$data['payment_method'];
        $tran_id = isset($data['tran_id']) ? (string)$data['tran_id'] : null;
        $status = isset($data['status']) ? (string)$data['status'] : null;

        try {
            $fund = new Fund();
            $fund->user_id = $user_id;
            $fund->amount = $amount;
            $fund->payment_method = $payment_method;
            $fund->tran_id = $tran_id;
            $fund->status = $status;
            $fund->save();

        } catch (Exception $e) {

        }


    }

    public static function make_transaction($data)
    {
        $user_id = $data['user_id'];
        $transaction_type = $data['transaction_type'];
        $amount = $data['amount'];
        $charge = isset($data['charge']) ? $data['charge'] : 0;
        $from_user_id = $data['from_user_id'];
        $note = isset($data['note']) ? $data['note'] : null;

        $ref_trans_id = $data['ref_trans_id'] ?? null;
        $debit = (strtolower($data['type']) == 'debit' ? $amount : 0);
        $credit = (strtolower($data['type']) == 'credit' ? $amount : 0);

        $to_user_id = $data['to_user_id'];

//        //below will be handled later
//        if (strtolower($data['type']) == 'debit' && EMoney::where('user_id', $from_user_id)->first()->current_balance < $amount) return null;

        $balance = self::update_emoney($user_id, $amount, $data['type'], $transaction_type, $charge);
//        if ($balance == null) {
//            return null;
//        }

        try {
            $transfer = new Transaction();
            $transfer->user_id = $user_id;
            $transfer->ref_trans_id = $ref_trans_id;
            $transfer->transaction_type = $transaction_type;
            $transfer->debit = $debit;
            $transfer->credit = $credit;
            $transfer->balance = $balance;
            $transfer->from_user_id = $from_user_id;
            $transfer->to_user_id = strtolower($transaction_type) == ADMIN_CHARGE ? self::get_admin_id() : $to_user_id;
            $transfer->note = $note;
            $transfer->transaction_id = Str::random(5) . Carbon::now()->timestamp;
            $transfer->save();
            return $transfer->transaction_id;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function update_emoney($user_id, $amount, $type, $transaction_type, $charge)
    {
        if (strtolower($transaction_type) == ADMIN_CHARGE) {
            try {
                $emoney = EMoney::where('user_id', 1)->first();
                $emoney->charge_earned += $charge;
                $emoney->save();
                return $emoney->current_balance;

            } catch (\Exception $e) {
                throw $e;
            }
        }

        $emoney = EMoney::where('user_id', $user_id)->first();
        if (strtolower($type) == 'debit') {
            try {
                $emoney->current_balance -= $amount;
                $emoney->charge_earned += $charge;
                $emoney->save();
                return $emoney->current_balance;

            } catch (\Exception $e) {
                throw $e;
            }

        } elseif (strtolower($type) == 'credit') {
            try {
                $emoney->current_balance += $amount;
                $emoney->charge_earned += $charge;
                $emoney->save();
                return $emoney->current_balance;

            } catch (\Exception $e) {
                throw $e;
            }

        }
    }

    public static function get_admin_id()
    {
        $admin_id = User::where('type', 0)->first()->id??1;
        return $admin_id;
    }

    public static function add_refer_commission($unique_id)
    {
        $user = User::where('unique_id', $unique_id)->first();
        $admin_id = self::get_admin_id();

        //START TRANSACTION
        DB::beginTransaction();
        $data = [];
        $data['from_user_id'] = $admin_id;
        $data['to_user_id'] = $user->id;

        try {
            //customer transaction
            $data['user_id'] = $data['to_user_id'];
            $data['type'] = 'credit';
            $data['transaction_type'] = 'refer_commission';
            $data['ref_trans_id'] = null;
            $data['amount'] = self::get_business_settings('refer_commission')??0;
            $customer_transaction = Helpers::make_transaction($data);

            if ($customer_transaction == null) {
                throw new TransactionFailedException('Transaction to sender is failed');
            }

            //admin transaction
            $data['user_id'] = $data['from_user_id'];
            $data['type'] = 'debit';
            $data['transaction_type'] = 'refer_commission';
            $data['ref_trans_id'] = $customer_transaction;
            $agent_transaction = Helpers::make_transaction($data);

            if ($agent_transaction == null) {
                throw new TransactionFailedException('Transaction from receiver is failed');
            }

            DB::commit();

        } catch (TransactionFailedException $e) {
            DB::rollBack();
            throw new TransactionFailedException('Refer commission failed');
        }

    }

    public static function send_transaction_notification($user_id, $amount, $transaction_type)
    {
        //send notification [receiver]
        $user = User::find($user_id);
        $value = Helpers::order_status_update_message($transaction_type);

        if(isset($user) && $user->fcm_token && $value)
        {
            $fcm_token = $user->fcm_token;
            $data = [
                'title' => '',
                'description' => self::set_symbol($amount) . ' ' . $value,
                'order_id' => '',
                'image' => '',
            ];

            try {
                Helpers::send_push_notif_to_device($fcm_token, $data);
                return true;
            } catch (\Exception $exception) {
                return false;
            }
        }

    }

    public static  function remove_dir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") Helpers::remove_dir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function file_remover(string $dir, $image)
    {
        if (!isset($image)) return true;

        if (Storage::disk('public')->exists($dir . $image)) Storage::disk('public')->delete($dir . $image);

        return true;
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $oldValue = env($envKey);
        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }

    public static function requestSender()
    {
        $class = new LaravelchkController();
        $response = $class->actch();
        return json_decode($response->getContent(),true);
    }
}

function translate($key)
{
    $local = session()->has('local') ? session('local') : 'en';
    App::setLocale($local);
    $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
    $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
    if (!array_key_exists($key, $lang_array)) {
        $lang_array[$key] = $processed_key;
        $str = "<?php return " . var_export($lang_array, true) . ";";
        file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
        $result = $processed_key;
    } else {
        $result = __('messages.' . $key);
    }
    return $result;
}
