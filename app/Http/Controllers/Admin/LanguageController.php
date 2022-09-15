<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LanguageController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.language.index');
    }

    public function store(Request $request)
    {
        $language = Helpers::get_business_settings('language');
        if(!isset($language)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'language'], [
                'value' => '[{"id":"1","name":"english","direction":"ltr","code":"en","status":1,"default":true}]'
            ]);
            $language = Helpers::get_business_settings('language');
        }
        $lang_array = [];
        $codes = [];
        foreach ($language as $key => $data) {
            if ($data['code'] != $request['code']) {
                if (!array_key_exists('default', $data)) {
                    $default = array('default' => ($data['code'] == 'en') ? true : false);
                    $data = array_merge($data, $default);
                }
                array_push($lang_array, $data);
                array_push($codes, $data['code']);
            }
        }
        array_push($codes, $request['code']);

        if (!file_exists(base_path('resources/lang/' . $request['code']))) {
            mkdir(base_path('resources/lang/' . $request['code']), 0777, true);
        }

        $lang_file = fopen(base_path('resources/lang/' . $request['code'] . '/' . 'messages.php'), "w") or die("Unable to open file!");
        $read = file_get_contents(base_path('resources/lang/en/messages.php'));
        fwrite($lang_file, $read);

        array_push($lang_array, [
            'id' => count($language) + 1,
            'name' => $request['name'],
            'code' => $request['code'],
            //'direction' => $request['direction'],
            'direction' => 'ltr',   //since no rtl in version 1.0
            'status' => 0,
            'default' => false,
        ]);

        BusinessSetting::updateOrInsert(['key' => 'language'], [
            'value' => $lang_array
        ]);

        Toastr::success('Language Added!');
        return back();
    }

    public function update_status(Request $request)
    {
        $language = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($language as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'] == 1 ? 0 : 1,
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang);
            } else {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang);
            }
        }
        $businessSetting = BusinessSetting::where('key', 'language')->update([
            'value' => $lang_array
        ]);

        return $businessSetting;
    }

    public function update_default_status(Request $request)
    {
        $language = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($language as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => 1,
                    'default' => true,
                ];
                array_push($lang_array, $lang);
            } else {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => false,
                ];
                array_push($lang_array, $lang);
            }
        }
        BusinessSetting::where('key', 'language')->update([
            'value' => $lang_array
        ]);

        Toastr::success('Default Language Changed!');
        return back();
    }

    public function update(Request $request)
    {
        $language = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($language as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],
                    'name' => $request['name'],
                    'direction' => $request['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'] ?? 0,
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang);
            } else {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang);
            }
        }
        BusinessSetting::where('key', 'language')->update([
            'value' => $lang_array
        ]);
        Toastr::success('Language updated!');
        return back();
    }

    public function translate($lang)
    {
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $lang_data = [];
        ksort($full_data);
        foreach ($full_data as $key => $data) {
            array_push($lang_data, ['key' => $key, 'value' => $data]);
        }
        return view('admin-views.business-settings.language.translate', compact('lang', 'lang_data'));
    }

    public function translate_key_remove(Request $request, $lang)
    {
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        unset($full_data[$request['key']]);
        $str = "<?php return " . var_export($full_data, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);
    }

    public function translate_submit(Request $request, $lang)
    {
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $full_data[$request['key']] = $request['value'];
        $str = "<?php return " . var_export($full_data, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);
    }

    public function delete($lang)
    {
        $language = Helpers::get_business_settings('language');

        $del_default = false;
        foreach ($language as $key => $data) {
            if ($data['code'] == $lang && array_key_exists('default', $data) && $data['default'] == true) {
                $del_default = true;
            }
        }

        $lang_array = [];
        foreach ($language as $key => $data) {
            if ($data['code'] != $lang) {
                $lang_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => ($del_default == true && $data['code'] == 'en') ? 1 : $data['status'],
                    'default' => ($del_default == true && $data['code'] == 'en') ? true : (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang_data);
            }
        }

        BusinessSetting::where('key', 'language')->update([
            'value' => $lang_array
        ]);

        $dir = base_path('resources/lang/' . $lang);
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);

        Toastr::success('Removed Successfully!');
        return back();
    }

    public function lang($local)
    {
        $direction = 'ltr';
        $language = Helpers::get_business_settings('language');
        foreach ($language as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $local);
        Session::put('direction', $direction);
        return redirect()->back();
    }
}
