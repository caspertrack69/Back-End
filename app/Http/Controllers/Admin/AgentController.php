<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function index()
    {
        return view('admin-views.agent.index');
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $agents = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $agents = new User();
        }

        $agents = $agents->agent()->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.agent.list', compact('agents', 'search'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $delivery_men = DeliveryMan::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('identity_number', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.agent.partials._table', compact('delivery_men'))->render()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'image' => 'required',
            'email' => '',
            'phone' => 'required|unique:users|min:5|max:20',
            'gender' => 'required',
            'occupation' => 'required',
            'password' => 'required|min:4|max:4',
        ],[
            'password.min'    => 'Password must contain 4 characters',
            'password.max'    => 'Password must contain 4 characters',
        ]);

        DB::transaction(function () use ($request) {
            $user = new User();
            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->image = Helpers::upload('agent/', 'png', $request->file('image'));
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->gender = $request->gender;
            $user->occupation = $request->occupation;
            $user->password = bcrypt($request->password);
            $user->type = 1;    //['Admin'=>0, 'Agent'=>1, 'Customer'=>2]
            $user->referral_id = $request->referral_id ?? null;
            $user->save();

            $user->find($user->id);
            $user->unique_id = $user->id . mt_rand(1111, 99999);
            $user->save();

            $emoney = new EMoney();
            $emoney->user_id = $user->id;
            $emoney->save();
        });

        Toastr::success(translate('Agent Added Successfully!'));
        return back();
    }

    public function edit($id)
    {
        $agent = User::find($id);
        return view('admin-views.agent.edit', compact('agent'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'occupation' => 'required',
        ]);

        $agent = User::find($id);
        $agent->f_name = $request->f_name;
        $agent->l_name = $request->l_name;
        $agent->image = $request->has('image') ? Helpers::update('agent/', $agent->image, 'png', $request->file('image')) : $agent->image;
        $agent->email = $request->has('email') ? $request->email : $agent->email;
        $agent->gender = $request->has('gender') ? $request->gender : $agent->gender;
        $agent->occupation = $request->occupation;
        if ($request->has('password') && strlen($request->password) > 3) {
            $agent->password = bcrypt($request->password);
        }
        $agent->type = AGENT_TYPE;
        $agent->referral_id = $request->referral_id ?? null;
        $agent->save();

        Toastr::success('Agent updated successfully!');
        return redirect(route('admin.agent.list'));
    }

    public function view($id)
    {
        $user = User::with('emoney')->find($id);
        return view('admin-views.view.details', compact('user'));
    }

    public function transaction(Request $request, $id)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);

            $users = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })->get()->pluck('id')->toArray();

            $transactions = Transaction::where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('from_user_id', $users)
                        ->orWhere('to_user_id', $users)
                        ->orWhere('transaction_type', 'like', "%{$value}%")
                        ->orWhere('balance', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $transactions = new Transaction();
        }


        $transactions = $transactions->where('user_id', $id)->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        $user = User::find($id);
        return view('admin-views.view.transaction', compact('user', 'transactions', 'search'));
    }

    public function status(Request $request)
    {
        $user = User::find($request->id);
        $user->is_active = !$user->is_active;
        $user->save();
        Toastr::success('Agent status updated!');

        return back();
    }

    public function get_kyc_request(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $agents = $this->user->where('is_kyc_verified', '!=', 1)->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $agents = $this->user->where('is_kyc_verified', '!=', 1);
        }

        $agents = $agents->orderByDesc('id')->agent()->paginate(Helpers::pagination_limit())->appends($query_param);
        //return gettype($agents[0]->identification_image);
        return view('admin-views.agent.kyc_list', compact('agents', 'search'));
    }

    public function update_kyc_status($id, $status)
    {
        $user = $this->user->find($id);
        if(!isset($user)) {
            Toastr::error(translate('agent not found'));
            return back();
        }
        $user->is_kyc_verified = in_array($status, [0,1,2]) ? $status : $user->is_kyc_verified;
        $user->save();

        Toastr::success(translate('Successfully updated.'));
        return back();
    }

}
