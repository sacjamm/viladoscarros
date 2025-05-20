<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PageHomeItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class PageHomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function edit() {
        $page_home = PageHomeItem::where('id',1)->first();
        return view('admin.page_home', compact('page_home'));
    }

    public function update(Request $request) {

        
        /*if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }*/
        
        if($request->search_background != '') {
            $request->validate([
                'search_background' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'search_background.image' => ERR_PHOTO_IMAGE,
                'search_background.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'search_background.max' => ERR_PHOTO_MAX
            ]);
            
            $currentSearchPath = public_path('uploads/site_photos/' . $request->current_search_background);
            if (file_exists($currentSearchPath)) {
                unlink($currentSearchPath);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }
            
            $rand_value = md5(mt_rand(11111111,99999999));
            $ext = $request->file('search_background')->extension();
            $final_name = $rand_value.'.'.$ext;
            $request->file('search_background')->move(public_path('uploads/site_photos/'), $final_name);
            $data['search_background'] = $final_name;
        }

        if($request->testimonial_background != '') {
            $request->validate([
                'testimonial_background' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'testimonial_background.image' => ERR_PHOTO_IMAGE,
                'testimonial_background.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'testimonial_background.max' => ERR_PHOTO_MAX
            ]);
            
            $currentTestimonialPath = public_path('uploads/site_photos/' . $request->current_testimonial_background);
            if (file_exists($currentTestimonialPath)) {
                unlink($currentTestimonialPath);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }
            
            $rand_value = md5(mt_rand(11111111,99999999));
            $ext = $request->file('testimonial_background')->extension();
            $final_name = $rand_value.'.'.$ext;
            $request->file('testimonial_background')->move(public_path('uploads/site_photos/'), $final_name);
            $data['testimonial_background'] = $final_name;
        }

        if($request->video_background != '') {
            $request->validate([
                'video_background' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'video_background.image' => ERR_PHOTO_IMAGE,
                'video_background.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'video_background.max' => ERR_PHOTO_MAX
            ]);
            
            $currentVideoPath = public_path('uploads/site_photos/' . $request->current_video_background);
            if (file_exists($currentVideoPath)) {
                unlink($currentVideoPath);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }
            
            $rand_value = md5(mt_rand(11111111,99999999));
            $ext = $request->file('video_background')->extension();
            $final_name = $rand_value.'.'.$ext;
            $request->file('video_background')->move(public_path('uploads/site_photos/'), $final_name);
            $data['video_background'] = $final_name;
        }


        $data['seo_title'] = $request->input('seo_title');
        $data['seo_meta_description'] = $request->input('seo_meta_description');
        $data['search_heading'] = $request->input('search_heading');
        $data['search_text'] = $request->input('search_text');
        $data['brand_heading'] = $request->input('brand_heading');
        $data['brand_subheading'] = $request->input('brand_subheading');
        $data['brand_total'] = $request->input('brand_total');
        $data['brand_status'] = $request->input('brand_status');
        $data['video_heading'] = $request->input('video_heading');
        $data['video_text'] = $request->input('video_text');
        $data['video_youtube_id'] = $request->input('video_youtube_id');
        $data['video_status'] = $request->input('video_status');
        $data['listing_heading'] = $request->input('listing_heading');
        $data['listing_subheading'] = $request->input('listing_subheading');
        $data['listing_total'] = $request->input('listing_total');
        $data['listing_status'] = $request->input('listing_status');
        $data['testimonial_heading'] = $request->input('testimonial_heading');
        $data['testimonial_subheading'] = $request->input('testimonial_subheading');
        $data['testimonial_status'] = $request->input('testimonial_status');
        $data['location_heading'] = $request->input('location_heading');
        $data['location_subheading'] = $request->input('location_subheading');
        $data['location_total'] = $request->input('location_total');
        $data['location_status'] = $request->input('location_status');
        
        PageHomeItem::where('id',1)->update($data);
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

}
