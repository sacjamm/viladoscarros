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
use App\Helpers\Helper;

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
                        /* $existingBrand->canal = 'dsautoestoque';
                          $existingBrand->listing_brand_photo = 'images/' . $slug . '.jpg';
                          $existingBrand->listing_brand_photo_png = 'images/' . $slug . '.png'; */
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
        $listing_brand = ListingBrand::findOrFail($id);
        $data = $request->only($listing_brand->getFillable());

        // Função auxiliar para converter imagem para webp com fundo branco (se necessário)
        function convertToWebp($sourcePath, $destinationPath, $mime) {
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $src = @imagecreatefrompng($sourcePath);
                    if (!$src)
                        return false;

                    $image = imagecreatetruecolor(imagesx($src), imagesy($src));
                    $white = imagecolorallocate($image, 255, 255, 255);
                    imagefill($image, 0, 0, $white);
                    imagecopy($image, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
                    imagedestroy($src);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($sourcePath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return false;
            }

            imagewebp($image, $destinationPath, 80);
            imagedestroy($image);
            return true;
        }

        if ($request->hasFile('listing_brand_photo')) {
            $request->validate([
                'listing_brand_photo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                    ], [
                'listing_brand_photo.image' => ERR_PHOTO_IMAGE,
                'listing_brand_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_brand_photo.max' => ERR_PHOTO_MAX
            ]);

            $currentPath = public_path('uploads/listing_brand_photos/' . $listing_brand->listing_brand_photo);
            if (file_exists($currentPath)) {
                unlink($currentPath);
            }

            $image = $request->file('listing_brand_photo');
            $mime = mime_content_type($image->getPathname());
            $filename = md5(mt_rand(11111111, 99999999)) . '.webp';
            $destination = public_path('uploads/listing_brand_photos/') . $filename;

            Helper::resizeAndConvertToWebp($image->getPathname(), $destination, 420, 320, 80);
            /*if (!convertToWebp($image->getPathname(), $destination, $mime)) {
                return redirect()->back()->with('error', 'Erro ao converter imagem para WebP');
            }*/

            unset($data['listing_brand_photo']);
            $data['listing_brand_photo'] = $filename;
        }

        if ($request->hasFile('listing_brand_photo_png')) {
            $request->validate([
                'listing_brand_photo_png' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                    ], [
                'listing_brand_photo_png.image' => ERR_PHOTO_IMAGE,
                'listing_brand_photo_png.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_brand_photo_png.max' => ERR_PHOTO_MAX
            ]);

            $currentPathPNG = public_path('uploads/listing_brand_photos/' . $listing_brand->listing_brand_photo_png);
            if (file_exists($currentPathPNG)) {
                unlink($currentPathPNG);
            }

            $imagePNG = $request->file('listing_brand_photo_png');
            $mimePNG = mime_content_type($imagePNG->getPathname());
            $filenamePNG = md5(mt_rand(11111111, 99999999)) . '.webp';
            $destinationPNG = public_path('uploads/listing_brand_photos/') . $filenamePNG;

            Helper::resizeAndConvertToWebp($imagePNG->getPathname(), $destinationPNG, 300, 200, 80);
            /*if (!convertToWebp($imagePNG->getPathname(), $destinationPNG, $mimePNG)) {
                return redirect()->back()->with('error', 'Erro ao converter imagem PNG para WebP');
            }*/

            unset($data['listing_brand_photo_png']);
            $data['listing_brand_photo_png'] = $filenamePNG;
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
            $data['listing_brand_slug'] = Str::slug($request->listing_brand_name);
        }

        if (preg_match('/\s/', $data['listing_brand_slug'])) {
            return redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }

        $listing_brand->fill($data)->save();

        return redirect()->route('admin_listing_brand_view')->with('success', SUCCESS_ACTION);
    }

    
    public function updateOld(Request $request, $id) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_brand = ListingBrand::findOrFail($id);
        $data = $request->only($listing_brand->getFillable());

        if ($request->hasFile('listing_brand_photo')) {

            $request->validate([
                'listing_brand_photo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
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
            $image = $request->file('listing_brand_photo');
            $extension = $image->getClientOriginalExtension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $filename = $rand_value . '.webp';
            $destination = public_path('uploads/listing_brand_photos/');

            // Converter para webp se não for webp
            if ($extension != 'webp') {
                $img = null;
                if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                    $img = imagecreatefromjpeg($image->getPathname());
                } elseif ($extension == 'png') {
                    $img = imagecreatefrompng($image->getPathname());
                }
                echo '<pre>';
                var_dump($extension, $filename, $img);
                echo '</pre>';
                die;
                if ($img) {
                    imagewebp($img, $destination . $filename, 80); // qualidade 80%
                    imagedestroy($img);
                }
            } else {
                // Apenas mover se já for webp
                $image->move($destination, $filename);
            }
            unset($data['listing_brand_photo']);
            $data['listing_brand_photo'] = $filename;

            /* $ext = $request->file('listing_brand_photo')->extension();
              $rand_value = md5(mt_rand(11111111, 99999999));
              $final_name = $rand_value . '.' . $ext;
              $request->file('listing_brand_photo')->move(public_path('uploads/listing_brand_photos/'), $final_name);

              unset($data['listing_brand_photo']);
              $data['listing_brand_photo'] = $final_name; */
        }
        if ($request->hasFile('listing_brand_photo_png')) {

            $request->validate([
                'listing_brand_photo_png' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                    ], [
                'listing_brand_photo_png.image' => ERR_PHOTO_IMAGE,
                'listing_brand_photo_png.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_brand_photo_png.max' => ERR_PHOTO_MAX
            ]);

            $currentBrandPathPNG = public_path('uploads/listing_brand_photos/' . $listing_brand->listing_brand_photo_png);
            if (file_exists($currentBrandPathPNG)) {
                unlink($currentBrandPathPNG);
            } else {
                // O arquivo não existe, você pode logar uma mensagem ou tratar o erro conforme necessário
                //Log::warning('O arquivo não foi encontrado: ' . $currentFaviconPath);
            }

            // Uploading the file
            $imagePNG = $request->file('listing_brand_photo_png');
            $extensionPNG = $imagePNG->getClientOriginalExtension();
            $rand_valuePNG = md5(mt_rand(11111111, 99999999));
            $filenamePNG = $rand_valuePNG . '.webp';
            $destinationPNG = public_path('uploads/listing_brand_photos/');

            // Converter para webp se não for webp
            if ($extensionPNG != 'webp') {
                $imgPNG = null;
                if (in_array(strtolower($extensionPNG), ['jpg', 'jpeg'])) {
                    $imgPNG = imagecreatefromjpeg($imagePNG->getPathname());
                } elseif ($extensionPNG == 'png') {
                    $imgPNG = imagecreatefrompng($imagePNG->getPathname());
                }

                if ($imgPNG) {
                    imagewebp($imgPNG, $destinationPNG . $filenamePNG, 80); // qualidade 80%
                    imagedestroy($imgPNG);
                }
            } else {
                // Apenas mover se já for webp
                $imagePNG->move($destinationPNG, $filenamePNG);
            }
            unset($data['listing_brand_photo_png']);
            $data['listing_brand_photo_png'] = $filenamePNG;
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
