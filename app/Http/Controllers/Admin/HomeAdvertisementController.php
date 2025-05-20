<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HomeAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class HomeAdvertisementController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function edit()
    {
        $adv_data = HomeAdvertisement::where('id',1)->first();
        return view('admin.advertisement_home', compact('adv_data'));
    }

    public function update(Request $request)
    {

        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/

        if($request->above_brand_1 != '')
        {
            $request->validate([
                'above_brand_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_brand_1.image' => ERR_PHOTO_IMAGE,
                'above_brand_1.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_brand_1.max' => ERR_PHOTO_MAX
            ]);
            $current_above_brand_1_Path = public_path('uploads/advertisements/' . $request->current_above_brand_1);
            if (file_exists($current_above_brand_1_Path)) {
                unlink($current_above_brand_1_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_brand_1')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_brand_1')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_brand_1'] = $final_name;
        }


        if($request->above_brand_2 != '')
        {
            $request->validate([
                'above_brand_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_brand_2.image' => ERR_PHOTO_IMAGE,
                'above_brand_2.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_brand_2.max' => ERR_PHOTO_MAX
            ]);
            $current_above_brand_2_Path = public_path('uploads/advertisements/' . $request->current_above_brand_2);
            if (file_exists($current_above_brand_2_Path)) {
                unlink($current_above_brand_2_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_brand_2')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_brand_2')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_brand_2'] = $final_name;
        }


        if($request->above_featured_listing_1 != '')
        {
            $request->validate([
                'above_featured_listing_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_featured_listing_1.image' => ERR_PHOTO_IMAGE,
                'above_featured_listing_1.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_featured_listing_1.max' => ERR_PHOTO_MAX
            ]);
            $current_above_featured_listing_1_Path = public_path('uploads/advertisements/' . $request->current_above_featured_listing_1);
            if (file_exists($current_above_featured_listing_1_Path)) {
                unlink($current_above_featured_listing_1_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_featured_listing_1')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_featured_listing_1')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_featured_listing_1'] = $final_name;
        }


        if($request->above_featured_listing_2 != '')
        {
            $request->validate([
                'above_featured_listing_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_featured_listing_2.image' => ERR_PHOTO_IMAGE,
                'above_featured_listing_2.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_featured_listing_2.max' => ERR_PHOTO_MAX
            ]);
            $current_above_featured_listing_2_Path = public_path('uploads/advertisements/' . $request->current_above_featured_listing_2);
            if (file_exists($current_above_featured_listing_2_Path)) {
                unlink($current_above_featured_listing_2_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_featured_listing_2')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_featured_listing_2')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_featured_listing_2'] = $final_name;
        }


        if($request->above_location_1 != '')
        {
            $request->validate([
                'above_location_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_location_1.image' => ERR_PHOTO_IMAGE,
                'above_location_1.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_location_1.max' => ERR_PHOTO_MAX
            ]);
            
            $current_above_location_1_Path = public_path('uploads/advertisements/' . $request->current_above_location_1);
            if (file_exists($current_above_location_1_Path)) {
                unlink($current_above_location_1_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_location_1')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_location_1')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_location_1'] = $final_name;
        }


        if($request->above_location_2 != '')
        {
            $request->validate([
                'above_location_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'above_location_2.image' => ERR_PHOTO_IMAGE,
                'above_location_2.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'above_location_2.max' => ERR_PHOTO_MAX
            ]);
            
            $current_above_location_2_Path = public_path('uploads/advertisements/' . $request->current_above_location_2);
            if (file_exists($current_above_location_2_Path)) {
                unlink($current_above_location_2_Path);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            $ext = $request->file('above_location_2')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('above_location_2')->move(public_path('uploads/advertisements/'), $final_name);

            $data['above_location_2'] = $final_name;
        }
        
        $data['above_brand_1_url'] = $request->input('above_brand_1_url');
        $data['above_brand_2_url'] = $request->input('above_brand_2_url');

        $data['above_featured_listing_1_url'] = $request->input('above_featured_listing_1_url');
        $data['above_featured_listing_2_url'] = $request->input('above_featured_listing_2_url');

        $data['above_location_1_url'] = $request->input('above_location_1_url');
        $data['above_location_2_url'] = $request->input('above_location_2_url');

        $data['above_brand_status'] = $request->input('above_brand_status');
        $data['above_featured_listing_status'] = $request->input('above_featured_listing_status');
        $data['above_location_status'] = $request->input('above_location_status');
        

        HomeAdvertisement::where('id',1)->update($data);
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }


    public function payment_edit()
    {
        $g_setting = GeneralSetting::where('id',1)->first();
        return view('admin.setting_payment', compact('g_setting'));
    }

    public function payment_update(Request $request)
    {
        
        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/

        $data['paypal_environment'] = $request->get('paypal_environment');
        $data['paypal_client_id'] = $request->get('paypal_client_id');
        $data['paypal_secret_key'] = $request->get('paypal_secret_key');

        GeneralSetting::where('id',1)->update($data);

        return redirect()->back()->with('success', SUCCESS_ACTION);
    }


}
