<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PageHomeItem;
use App\Models\Banner;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\HomeAdvertisement;
use App\Models\Testimonial;
use App\Models\PageListingItem;
use App\Models\PageBlogItem;
use App\Models\Blog;
use App\Models\Category;
use DB;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeController extends Controller {

    public function converterImagensParaWebp() {
        $diretorio = public_path('uploads/listing_featured_photos');
        $imagens = File::files($diretorio);

        foreach ($imagens as $imagem) {
            $extensao = strtolower($imagem->getExtension());

            if (in_array($extensao, ['jpg', 'jpeg', 'png'])) {
                $caminhoOriginal = $imagem->getRealPath();
                $novoNome = Str::before($imagem->getFilename(), '.' . $extensao) . '.webp';
                $caminhoWebp = $diretorio . '/' . $novoNome;

                if (!file_exists($caminhoWebp)) {
                    if ($extensao === 'jpg' || $extensao === 'jpeg') {
                        $imageResource = imagecreatefromjpeg($caminhoOriginal);
                    } else {
                        $imageResource = imagecreatefrompng($caminhoOriginal);
                        imagepalettetotruecolor($imageResource);
                        imagealphablending($imageResource, true);
                        imagesavealpha($imageResource, true);
                    }

                    if ($imageResource) {
                        imagewebp($imageResource, $caminhoWebp, 80);
                        imagedestroy($imageResource);
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Conversão finalizada.']);
    }  

    public function uploadBanner(Request $request) {
        if (!$request->hasFile('image')) {
            return back()->with('error', 'Nenhuma imagem enviada.');
        }

        $image = $request->file('image');
        $extension = strtolower($image->getClientOriginalExtension());

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return back()->with('error', 'Formato de imagem não suportado. Use JPG, JPEG ou PNG.');
        }

        $fileName = Str::uuid() . '.' . $extension;
        $webpFileName = Str::uuid() . '.webp';

        // Caminhos
        $uploadPath = public_path('uploads/banner_photos/');
        $originalPath = $uploadPath . $fileName;
        $webpPath = $uploadPath . $webpFileName;

        // Cria diretório se não existir
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Salva a imagem original
        $image->move($uploadPath, $fileName);

        // Converte para WebP
        $imageResource = null;
        if ($extension === 'jpeg' || $extension === 'jpg') {
            $imageResource = imagecreatefromjpeg($originalPath);
        } elseif ($extension === 'png') {
            $imageResource = imagecreatefrompng($originalPath);
            imagepalettetotruecolor($imageResource);
            imagealphablending($imageResource, true);
            imagesavealpha($imageResource, true);
        }

        if ($imageResource) {
            imagewebp($imageResource, $webpPath, 80);
            imagedestroy($imageResource);
        }

        return back()->with('success', 'Banner enviado e convertido para WebP com sucesso!');
    }

    public function logout_admin() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login');
    }

    public function home() {
        Auth::guard('admin')->logout();
        session()->flush();
        return redirect()->route('admin_login');
    }

    public function index() {

        $user_data = Auth::user();
        $adv_home_data = HomeAdvertisement::where('id', 1)->first();

        $page_home_items = PageHomeItem::where('id', 1)->first();

        $page_listing_item = PageListingItem::where('id', 1)->first();

        $testimonials = Testimonial::get();

        $banners = Banner::orderBy('id', 'asc')->get();

        $blog = PageBlogItem::where('id', 1)->first();

        $blog_items = Blog::with('rCategory')
                ->orderby('id', 'desc')
                ->paginate(3);

        $blog_items_no_pagi = Blog::orderby('id', 'desc')->get();
        $categories = Category::get();

        $listing_locations = ListingLocation::withCount('rListing')
                ->orderBy('listing_location_name', 'asc')
                ->get();
        $listing_brands = ListingBrand::withCount('rListing')
                ->orderBy('listing_brand_name', 'asc')
                ->get();

        $orderwise_listing_brands = DB::select('SELECT *
                        FROM listing_brands as r1
                        LEFT JOIN (SELECT listing_brand_id, count(*) as total
                            FROM listings as l
                            JOIN listing_brands as lc
                            ON l.listing_brand_id = lc.id
                            GROUP BY listing_brand_id
                            ORDER BY total DESC) as r2
                        ON r1.id = r2.listing_brand_id
                        ORDER BY r2.total DESC');

        $orderwise_listing_locations = DB::select('SELECT *
                        FROM listing_locations as r1
                        LEFT JOIN (SELECT listing_location_id, count(*) as total
                            FROM listings as l
                            JOIN listing_locations as ll
                            ON l.listing_location_id = ll.id
                            GROUP BY listing_location_id
                            ORDER BY total DESC) as r2
                        ON r1.id = r2.listing_location_id
                        ORDER BY r2.total DESC');

        /* $listings = Listing::with('rListingBrand', 'rListingLocation')
          ->orderByDesc(DB::raw("is_featured = 'Yes'"))
          ->orderBy('listing_price', 'DESC')
          ->where('listing_status', 'Active')
          ->where('is_featured', 'Yes')
          ->inRandomOrder()
          ->get(); */
        $listings = Listing::with('rListingBrand', 'rListingLocation')
                ->orderBy('id', 'desc')
                ->where('listing_status', 'Active')
                ->where('is_featured', 'Yes')
                ->inRandomOrder()
                ->get();

        return view('front.index', compact('adv_home_data', 'page_home_items',
                        'orderwise_listing_brands', 'orderwise_listing_locations', 'listings',
                        'listing_brands', 'listing_locations', 'testimonials', 'page_listing_item', 'user_data',
                        'blog', 'blog_items', 'blog_items_no_pagi', 'categories', 'banners'));
    }
}
