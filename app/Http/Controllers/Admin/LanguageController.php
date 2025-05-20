<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class LanguageController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function language_menu_text() {
        $language_data = json_decode(file_get_contents(resource_path('lang/json_admin/menu_texts.json')));
        return view('admin.language_menu_text_view', compact('language_data'));
    }

    public function language_menu_text_update(Request $request) {

       /* if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/

        // Form Data
        $arr_key = [];
        foreach($request->key_arr as $item) {
            $arr_key[] = $item;
        }
        $arr_value = [];
        foreach($request->value_arr as $item) {
            $arr_value[] = $item;
        }

        // Updating Data
        for($i=0;$i<count($arr_key);$i++) {
            $data[$arr_key[$i]] = $arr_value[$i];
        }

        // New Data inserting into the existing json
        $new_json = json_encode($data,JSON_PRETTY_PRINT);
        file_put_contents(resource_path('lang/json_admin/menu_texts.json'), $new_json);

        return redirect()->back()->with('success', SUCCESS_ACTION);
    }


    public function language_website_text() {
        $language_data = json_decode(file_get_contents(resource_path('lang/json_admin/website_texts.json')));
        return view('admin.language_website_text_view', compact('language_data'));
    }

    public function language_website_text_update(Request $request) {

        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/

        // Form Data
        $arr_key = [];
        foreach($request->key_arr as $item) {
            $arr_key[] = $item;
        }
        $arr_value = [];
        foreach($request->value_arr as $item) {
            $arr_value[] = $item;
        }

        // Updating Data
        for($i=0;$i<count($arr_key);$i++) {
            $data[$arr_key[$i]] = $arr_value[$i];
        }

        // New Data inserting into the existing json
        $new_json = json_encode($data,JSON_PRETTY_PRINT);
        file_put_contents(resource_path('lang/json_admin/website_texts.json'), $new_json);
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function language_notification_text() {
        $language_data = json_decode(file_get_contents(resource_path('lang/json_admin/notification_texts.json')));
        return view('admin.language_notification_text_view', compact('language_data'));
    }

    public function language_notification_text_update(Request $request) {

        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/

        // Form Data
        $arr_key = [];
        foreach($request->key_arr as $item) {
            $arr_key[] = $item;
        }
        $arr_value = [];
        foreach($request->value_arr as $item) {
            $arr_value[] = $item;
        }

        // Updating Data
        for($i=0;$i<count($arr_key);$i++) {
            $data[$arr_key[$i]] = $arr_value[$i];
        }

        // New Data inserting into the existing json
        $new_json = json_encode($data,JSON_PRETTY_PRINT);
        file_put_contents(resource_path('lang/json_admin/notification_texts.json'), $new_json);
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }


    public function language_admin_panel_text() {
        $language_data = json_decode(file_get_contents(resource_path('lang/json_admin/admin_panel_texts.json')));
        return view('admin.language_admin_panel_text_view', compact('language_data'));
    }

    public function language_admin_panel_text_update(Request $request) {

        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/
        
        // Form Data
        $arr_key = [];
        foreach($request->key_arr as $item) {
            $arr_key[] = $item;
        }
        $arr_value = [];
        foreach($request->value_arr as $item) {
            $arr_value[] = $item;
        }

        // Updating Data
        for($i=0;$i<count($arr_key);$i++) {
            $data[$arr_key[$i]] = $arr_value[$i];
        }

        // New Data inserting into the existing json
        $new_json = json_encode($data,JSON_PRETTY_PRINT);
        file_put_contents(resource_path('lang/json_admin/admin_panel_texts.json'), $new_json);
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

}
