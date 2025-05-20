<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\PageAboutItem;
use Illuminate\Http\Request;
use DB; 
use Illuminate\Support\Facades\Mail;
use Auth;

class LojasController extends Controller
{
    public function index() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $about_data = PageAboutItem::where('id', 1)->first();
        $lojas = User::where('status', 'Active')->get();
        
        $current_auth_user_id = 0;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
        }
        
        return view('front.lojas', compact('about_data','g_setting','lojas','current_auth_user_id'));
    }
}
