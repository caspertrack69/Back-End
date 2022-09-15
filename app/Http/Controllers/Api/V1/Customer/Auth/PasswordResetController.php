<?php

namespace App\Http\Controllers\Api\V1\Customer\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Http\Controllers\Admin\Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function bcrypt;
use function now;
use function response;

class PasswordResetController extends Controller
{
    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $customer = User::where(['phone' => $request->phone])->first();

        //type check
        if($customer->type != CUSTOMER_TYPE) {
            return response()->json(['errors' => [
                ['code' => 'forbidden', 'message' => 'Access forbidden!']
            ]], 403);
        }

        if (isset($customer)) {
            $otp = rand(1000, 9999);
            if(env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }

            DB::table('password_resets')->updateOrInsert(['phone' => $request->phone], [
                'token' => $otp,
                'created_at' => now(),
            ]);

            try {
                $response = SMS_module::send($customer['phone'], $otp);
                return response()->json([
                    'message' => 'OTP sent successfully.'
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => $response
                ], 200);
            }
        }
        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => 'Customer not found!']
        ]], 404);
    }

    public function verify_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = DB::table('password_resets')->where(['token' => $request['otp'], 'phone' => $request->phone])->first();
        if (isset($data)) {
            return response()->json(['message' => "OTP found, you can proceed"], 200);
        }
        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid OTP.']
        ]], 400);
    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = DB::table('password_resets')->where(['phone' => $request->phone])
            ->where(['token' => $request['otp']])->first();

        if (isset($data)) {

            if ($request['password'] == $request['confirm_password']) {
                $customer = User::where(['phone' => $request->phone])->first();
                $customer->password = bcrypt($request['confirm_password']);
                $customer->save();

                DB::table('password_resets')
                    ->where(['phone' => $request->phone])
                    ->where(['token' => $request['otp']])->delete();

                return response()->json(['message' => 'Password changed successfully.'], 200);
            }
            return response()->json(['errors' => [
                ['code' => 'mismatch', 'message' => "Password didn't match!"]
            ]], 401);
        }
        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid OTP.']
        ]], 400);
    }
}
