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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'image' => 'required|image|mimes:jpeg,jpg,png,webp'
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'banners'");
        $ai_id = $statement[0]->Auto_increment;

        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        $rand_value = md5(mt_rand(11111111, 99999999));
        $filename = $rand_value . '.webp';
        $destination = public_path('uploads/banner_photos/' . $filename);

        $manager = new ImageManager(new Driver());
        $sourcePath = public_path($image->getPathname());
        $imagem = $manager->read($sourcePath);
        $imagem->resize(1190, null);
        $imagem->toWebp(75)->save($destination);

        

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
        //$data['description'] = $request->input('description');
        if ($request->hasFile('image')) {
            $currentPath = public_path('uploads/banner_photos/' . $banner->image);
            if (file_exists($currentPath)) {
                unlink($currentPath);
                @unlink(public_path('uploads/banner_photos/' . pathinfo($banner->image, PATHINFO_FILENAME) . '-768.webp'));
                @unlink(public_path('uploads/banner_photos/' . pathinfo($banner->image, PATHINFO_FILENAME) . '-480.webp'));
            }

            $image = $request->file('image');
            //$extension = $image->getClientOriginalExtension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $base_filename = $rand_value;
            $filename = $rand_value . '.webp';
            //$destination = public_path('uploads/banner_photos/' . $filename);
            $manager = new ImageManager(new Driver());

            try {
                $imagem = $manager->read($image->getPathname());
                $imagem->scale(width: 1190);
                $data['largura'] = $imagem->width();
                $data['altura'] = $imagem->height();
                $imagem->toWebp(70)
                        ->save(public_path('uploads/banner_photos/' . $filename));

                $imagem768 = $manager->read($image->getPathname());
                $imagem768->scale(width: 768);
                $data['largura_768'] = $imagem768->width();
                $data['altura_768'] = $imagem768->height();
                $imagem768->toWebp(70)
                        ->save(public_path('uploads/banner_photos/' . $base_filename . '-768.webp'));

                // === VERSÃO 480px ===
                $imagem480 = $manager->read($image->getPathname());
                $imagem480->scale(width: 480);
                $data['largura_480'] = $imagem480->width();
                $data['altura_480'] = $imagem480->height();
                $imagem480->toWebp(70)->save(public_path('uploads/banner_photos/' . $base_filename . '-480.webp'));
            } catch (\Exception $e) {
                return back()->with('error', 'Erro ao processar imagem: ' . $e->getMessage());
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
