<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ListingAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class BannerController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $banners = Banner::all();
        return view('admin.banner_view', compact('banners'));
    }

    public function create() {
        return view('admin.banner_create');
    }

    public function storeOld(Request $request) {
        $statement = DB::select("SHOW TABLE STATUS LIKE 'banners'");
        $ai_id = $statement[0]->Auto_increment;
        $rand_value = md5(mt_rand(11111111, 99999999));
        $ext = $request->file('image')->extension();
        $final_name = $rand_value . '.' . $ext;
        $request->file('image')->move(public_path('uploads/banner_photos/'), $final_name);
        $banner = new Banner();
        $data = $request->only($banner->getFillable());
        $data['description'] = $request->input('description');
        unset($data['image']);
        $data['image'] = $final_name;
        $banner->fill($data)->save();
        return redirect()->route('admin_banner_view')->with('success', SUCCESS_DATA_ADD);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp'
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'banners'");
        $ai_id = $statement[0]->Auto_increment;

        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        $rand_value = md5(mt_rand(11111111, 99999999));
        $filename = $rand_value . '.webp';
        $destination = public_path('uploads/banner_photos/');

        // Converter para webp se não for webp
        if ($extension != 'webp') {
            $img = null;
            if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                $img = imagecreatefromjpeg($image->getPathname());
            } elseif ($extension == 'png') {
                $img = imagecreatefrompng($image->getPathname());
            }

            if ($img) {
                imagewebp($img, $destination . $filename, 80); // qualidade 80%
                imagedestroy($img);
            }
        } else {
            // Apenas mover se já for webp
            $image->move($destination, $filename);
        }

        $banner = new Banner();
        $data = $request->only($banner->getFillable());
        $data['description'] = $request->input('description');
        unset($data['image']);
        $data['image'] = $filename;
        $banner->fill($data)->save();

        return redirect()->route('admin_banner_view')->with('success', SUCCESS_DATA_ADD);
    }

    public function edit($id) {
        $banner = Banner::findOrFail($id);
        return view('admin.banner_edit', compact('banner'));
    }

    public function update(Request $request, $id) {
        $banner = Banner::findOrFail($id);
        $data = $request->only($banner->getFillable());
        $data['description'] = $request->input('description');
        if ($request->hasFile('image')) {
            $currentPath = public_path('uploads/banner_photos/' . $banner->image);
            if (file_exists($currentPath)) {
                unlink($currentPath);
            }

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $filename = $rand_value . '.webp';
            $destination = public_path('uploads/banner_photos/');

            if ($extension != 'webp') {
                $img = null;
                if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                    $img = imagecreatefromjpeg($image->getPathname());
                } elseif ($extension == 'png') {
                    $img = imagecreatefrompng($image->getPathname());
                }

                if ($img) {
                    imagewebp($img, $destination . $filename, 80);
                    imagedestroy($img);
                }
            } else {
                $image->move($destination, $filename);
            }

            unset($data['image']);
            $data['image'] = $filename;
        }
        $banner->fill($data)->save();
        return redirect()->route('admin_banner_view')->with('success', SUCCESS_DATA_UPDATE);
    }

    public function destroy($id) {
        $banner = Banner::findOrFail($id);
        $currentBlogPath = public_path('uploads/banner_photos/' . $banner->image);
        if (file_exists($currentBlogPath)) {
            unlink($currentBlogPath);
        }
        $banner->delete();
        return redirect()->back()->with('success', SUCCESS_DATA_DELETE);
    }
}
