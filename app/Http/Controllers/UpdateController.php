<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 180);

use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function update_software_index(){
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID','MzczNTQxNDc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME',$request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE',$request['purchase_key']);
        Helpers::setEnvironmentValue('APP_MODE','live');
        Helpers::setEnvironmentValue('SOFTWARE_VERSION','1.2');
        Helpers::setEnvironmentValue('APP_NAME','6cash'.time());

        $data = Helpers::requestSender();
        if (!$data['active']) {
            return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
        }

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect('/admin/auth/login');
    }
}
