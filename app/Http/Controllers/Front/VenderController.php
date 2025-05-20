<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PageVenderItem;
use Illuminate\Http\Request;
use DB;

class VenderController extends Controller
{
    public function index() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $privacy_data = PageVenderItem::where('id', 1)->first();
        $faqs = DB::table('faqs')->orderby('faq_order', 'asc')->get();
        return view('front.quero_vender', compact('privacy_data','g_setting','faqs'));
    }
}
