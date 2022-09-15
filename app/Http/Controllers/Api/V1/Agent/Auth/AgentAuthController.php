<?php

namespace App\Http\Controllers\Api\V1\Agent\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgentRequestMoneyResource;
use App\Http\Resources\RequestMoneyResource;
use App\Models\EMoney;
use App\Models\LinkedWebsite;
use App\Models\PhoneVerification;
use App\Models\RequestMoney;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class AgentAuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login_old(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = Helpers::filter_phone($request->phone);

        $user = User::agent()->where('phone', $user_id)->first();
        if (isset($user)) {
            $data = [
                'phone' => $user->phone,
                'password' => $request->password
            ];

            if (auth()->attempt($data)) {
                auth()->user()->update([
                    'last_active_at' => now(),
                ]);
                $token = $user->createToken('Token Name')->accessToken;
                return response()->json(['token' => $token], 200);
            }
        }

        $errors = [];
        array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential.']);
        return response()->json([
            'errors' => $errors
        ], 401);

    }

    public function login(Request $request)
    {
        $user = User::agent()->where('phone', $request->phone)->first();

        //availability check
        if (!isset($user)) {
            return response()->json(['status_code' => 404, 'message' => translate('User Not found')], 404);
        }

        //status active check
        if (isset($user->is_active) && $user->is_active == false) {
            return response()->json(['status_code' => 401, 'message' => translate('You have been blocked')], 401);
        }

        //password check
        if (!Hash::check($request['password'], $user['password'])) {
            return response()->json(['status_code' => 401, 'message' => translate('Password mismatched')], 401);
        }

        //if everything is okay
        $user->update(['last_active_at' => now()]);
        $user->AauthAcessToken()->delete();
        $token = $user->createToken('AgentAuthToken')->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function get_agent(Request $request)
    {
//        $agent = User::find($request->id);
//        return $agent;

        $customer = User::with('emoney')->agent()->find($request->user()->id);
        if(isset($customer)) {
            $data = [];
            $data['name'] = $customer['f_name'] . ' ' . $customer['l_name'];
            $data['phone'] = $customer['phone'];
            $data['type'] = $customer['type'];
            $data['image'] = $customer['image'];
            $qr = Helpers::get_qrcode($data);

            return response()->json(
                [
                    'f_name' => $customer->f_name,
                    'l_name' => $customer->l_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'image' => $customer->image,
                    'type' => $customer->type,
                    'gender' => $customer->gender,
                    'occupation' => $customer->occupation,
                    'two_factor' => (integer)$customer->two_factor,
                    'fcm_token' => $customer->fcm_token,
                    'balance' => (float)$customer->emoney->current_balance,
                    'unique_id' => $customer->unique_id,
                    'qr_code' => strval($qr),
                    'is_kyc_verified' => (int)$customer->is_kyc_verified,
                ]
                , 200);
        } else {
            return response()->json([], 200);
        }
    }

    //fcm
    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //type check
        if($request->user()->type != AGENT_TYPE) {
            return response()->json(['message' => 'Unauthorized request'], 403);
        }

        try {
            $user = User::find($request->user()->id);
            $user->fcm_token = $request->token;
            $user->save();
            return response()->json(['message' => 'FCM token successfully updated'], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    //logout
    public function logout(Request $request)
    {
        //type check
        if($request->user()->type != AGENT_TYPE) {
            return response()->json(['message' => 'Unauthorized request'], 403);
        }

        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
            return response()->json(['message' => 'Logout successful'], 200);
        } else {
            return response()->json(['message' => 'Logout failed'], 403);
        }
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => '',
            'image' => '',
            'gender' => 'required',
            'occupation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = User::find($request->user()->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->email = $request->email;
        $user->image = $request->has('image') ? Helpers::update('agent/', $user->image, 'png', $request->image) : $user->image;
        $user->gender = $request->gender;
        $user->occupation = $request->occupation;
        $user->save();
        return response()->json(['message' => 'Profile successfully updated'], 200);
    }

    //

    //PIN
    public function verify_pin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if(Helpers::pin_check($request->user()->id, $request->pin)) {
            return response()->json(['message' => translate('PIN is correct')], 200);
        }else{
            return response()->json(['message' => translate('PIN is incorrect')], 403);
        }
    }

    public function change_pin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_pin' => 'required|min:4|max:4',
            'new_pin' => 'required|min:4|max:4',
            'confirm_pin' => 'required|min:4|max:4',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //PIN Check
        if (!Helpers::pin_check($request->user()->id, $request->old_pin)) {
            return response()->json(['message' => translate('Old PIN is incorrect')], 401);
        }

        //PIN & Confirm PIN Match
        if ($request->new_pin != $request->confirm_pin) {
            return response()->json(['message' => translate('PIN Mismatch')], 404);
        }

        //Change PIN
        try {
            $user = User::find($request->user()->id);
            $user->password = bcrypt($request->confirm_pin);
            $user->save();
            return response()->json(['message' => translate('PIN updated successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => translate('PIN updated failed')], 401);
        }

    }

    //OTP
    public function resend_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:5|max:20|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $phone = $request['phone'];
        try {
            $otp = mt_rand(1000, 9999);
            if(env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }
            DB::table('phone_verifications')->updateOrInsert(['phone' => $phone], [
                'otp' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $response = SMS_module::send($phone, $otp);
            return response()->json([
                'message' => translate('OTP sent successfully'),
                'otp' => 'active'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => translate('OTP sent failed'),
                'otp' => 'inactive'
            ], 200);
        }
    }

    public function verify_otp($phone, $otp)
    {
        $verify = PhoneVerification::where(['phone' => $phone, 'otp' => $otp])->first();

        if (isset($verify)) {
            $verify->delete();
            return true;
        } else {
            return false;
        }
    }

    //TWO FACTOR
    public function update_two_factor(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            $user->two_factor = !$request->user()->two_factor;
            $user->save();
            return response()->json(['message' => translate('Two factor updated')], 200);

        } catch (\Exception $e) {
            return response()->json(['errors' => 'failed'], 403);
        }

    }

    //requested money
    public function get_requested_money(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $request_money = RequestMoney::where('from_user_id', $request->user()->id);

        $request_money->when(request('type') == 'pending', function ($q) {
            return $q->where('type', 'pending');
        });
        $request_money->when(request('type') == 'approved', function ($q) {
            return $q->where('type', 'approved');
        });
        $request_money->when(request('type') == 'denied', function ($q) {
            return $q->where('type', 'denied');
        });

        $request_money = AgentRequestMoneyResource::collection($request_money->latest()->paginate($limit, ['*'], 'page', $offset));
        return [
            'total_size' => $request_money->total(),
            'limit' => $limit,
            'offset' => $offset,
            'requested_money' => $request_money->items()
        ];
    }

    public function linked_website(Request $request)
    {
        $linked_websites = LinkedWebsite::select('name', 'image', 'url')->active()->orderBy("id", "desc")->take(20)->get();
        return $linked_websites;
    }

    public function update_kyc_information(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identification_number' => 'required',
            'identification_type' => 'required|in:passport,driving_licence,nid,trade_license',
            'identification_image' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $identity_images = [];
        foreach ($request->identification_image as $image) {
            $identity_images[] = Helpers::file_uploader('user/identity/', 'png', $image);
        }

        $user = $this->user->find($request->user()->id);
        if($user->is_kyc_verified == 1) {
            return response()->json(Helpers::response_formatter(DEFAULT_FAIL_200), 200);
        }
        $user->identification_number = $request->identification_number;
        $user->identification_type = $request->identification_type;
        $user->identification_image = $identity_images;
        $user->is_kyc_verified = 0;
        $user->save();

        return response()->json(Helpers::response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
