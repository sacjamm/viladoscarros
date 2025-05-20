<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;

class ListingBrandController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
        return view('admin.listing_brand_view', compact('listing_brand'));
    }

    public function create() {
        $listing_brand = ListingBrand::get();
        return view('admin.listing_brand_create', compact('listing_brand'));
    }

    public function import(Request $request) {
        $request->validate([
            'marcas' => 'required|mimes:csv,txt|max:2048'
        ]);

        if ($request->hasFile('marcas')) {
            $file = $request->file('marcas');

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {

                $header = fgetcsv($handle, 1000, ';');

                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    $marcaName = strtoupper($data[1]);
                    $slug = Str::slug($marcaName);

                    $existingBrand = ListingBrand::where('listing_brand_slug', $slug)->first();

                    if (!$existingBrand) {
                        $listing_brand = new ListingBrand();
                        $listing_brand->marca_id = $data[0];
                        $listing_brand->seo_title = $marcaName;
                        $listing_brand->listing_brand_name = $marcaName;
                        $listing_brand->listing_brand_slug = $slug;
                        $listing_brand->canal = 'import';
                        $listing_brand->listing_brand_photo = 'images/' . $slug . '.jpg';
                        $listing_brand->listing_brand_photo_png = 'images/' . $slug . '.png';
                        $listing_brand->save();
                    } else {
                        /*$existingBrand->canal = 'dsautoestoque';
                        $existingBrand->listing_brand_photo = 'images/' . $slug . '.jpg';
                        $existingBrand->listing_brand_photo_png = 'images/' . $slug . '.png';*/
                        $existingBrand->marca_id = $data[0];
                        $existingBrand->save();
                    }
                }
                fclose($handle);

                return redirect()->back()->with('success', 'CSV importado com sucesso!');
            }
        } else {
            return redirect()->back()->with('error', 'Erro ao carregar o arquivo.');
        }
    }

    public function store(Request $request) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $request->validate([
            'listing_brand_name' => 'required|unique:listing_brands',
            'listing_brand_slug' => 'unique:listing_brands',
            'listing_brand_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ], [
            'listing_brand_name.required' => ERR_NAME_REQUIRED,
            'listing_brand_name.unique' => ERR_NAME_EXIST,
            'listing_brand_slug.unique' => ERR_SLUG_UNIQUE,
            'listing_brand_photo.required' => ERR_PHOTO_REQUIRED,
            'listing_brand_photo.image' => ERR_PHOTO_IMAGE,
            'listing_brand_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'listing_brand_photo.max' => ERR_PHOTO_MAX
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'listing_brands'");
        $ai_id = $statement[0]->Auto_increment;

        $ext = $request->file('listing_brand_photo')->extension();
        $rand_value = md5(mt_rand(11111111, 99999999));
        $final_name = $rand_value . '.' . $ext;
        $request->file('listing_brand_photo')->move(public_path('uploads/listing_brand_photos/'), $final_name);

        $listing_brand = new ListingBrand();
        $data = $request->only($listing_brand->getFillable());
        if (empty($data['listing_brand_slug'])) {
            unset($data['listing_brand_slug']);
            $data['listing_brand_slug'] = Str::slug($request->listing_brand_name);
        }

        if (preg_match('/\s/', $data['listing_brand_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }

        unset($data['listing_brand_photo']);
        $data['listing_brand_photo'] = $final_name;
        $data['canal'] = 'website';

        $listing_brand->fill($data)->save();

        return redirect()->route('admin_listing_brand_view')->with('success', SUCCESS_ACTION);
    }

    public function edit($id) {
        $listing_brand = ListingBrand::findOrFail($id);
        return view('admin.listing_brand_edit', compact('listing_brand'));
    }

    public function update(Request $request, $id) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_brand = ListingBrand::findOrFail($id);
        $data = $request->only($listing_brand->getFillable());

        if ($request->hasFile('listing_brand_photo')) {

            $request->validate([
                'listing_brand_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ], [
                'listing_brand_photo.image' => ERR_PHOTO_IMAGE,
                'listing_brand_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_brand_photo.max' => ERR_PHOTO_MAX
            ]);

            $currentBrandPath = public_path('uploads/listing_brand_photos/' . $listing_brand->listing_brand_photo);
            if (file_exists($currentBrandPath)) {
                unlink($currentBrandPath);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            // Uploading the file
            $ext = $request->file('listing_brand_photo')->extension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $final_name = $rand_value . '.' . $ext;
            $request->file('listing_brand_photo')->move(public_path('uploads/listing_brand_photos/'), $final_name);

            unset($data['listing_brand_photo']);
            $data['listing_brand_photo'] = $final_name;
        }

        $request->validate([
            'listing_brand_name' => [
                'required',
                Rule::unique('listing_brands')->ignore($id),
            ],
            'listing_brand_slug' => [
                Rule::unique('listing_brands')->ignore($id),
            ]
                ], [
            'listing_brand_name.required' => ERR_NAME_REQUIRED,
            'listing_brand_name.unique' => ERR_NAME_EXIST,
            'listing_brand_slug.unique' => ERR_SLUG_UNIQUE,
        ]);

        if (empty($data['listing_brand_slug'])) {
            unset($data['listing_brand_slug']);
            $data['listing_brand_slug'] = Str::slug($request->listing_brand_name);
        }

        if (preg_match('/\s/', $data['listing_brand_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }

        $listing_brand->fill($data)->save();

        return redirect()->route('admin_listing_brand_view')->with('success', SUCCESS_ACTION);
    }

    public function destroy($id) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $tot = Listing::where('listing_brand_id', $id)->count();
        if ($tot) {
            return Redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        $listing_brand = ListingBrand::findOrFail($id);
        $currentBrandPath = public_path('uploads/listing_brand_photos/' . $listing_brand->listing_brand_photo);
        if (file_exists($currentBrandPath)) {
            unlink($currentBrandPath);
        } else {
            // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
            //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
        }
        $listing_brand->delete();

        // Success Message and redirect
        return Redirect()->back()->with('success', SUCCESS_ACTION);
    }
}
