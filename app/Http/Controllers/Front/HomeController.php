<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PageHomeItem;
use App\Models\User;
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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class HomeController extends Controller {

    public function montarUrlComCnpjs() {
        // Consulta os CNPJs da tabela users com status 'Active'
        $cnpjs = User::where('status', 'Active')->pluck('cnpj')->toArray();

        // Filtra os CNPJs para remover valores vazios ou inválidos
        $cnpjs = array_filter($cnpjs);

        // Limpa os CNPJs usando a função limparCNPJ
        $cnpjs = array_map([$this, 'limparCNPJ'], $cnpjs); // Use $this para chamar a função
        // Monta a string de CNPJs
        $cnpjString = implode(',', $cnpjs);

        // Monta a URL
        $url = "https://xml.dsautoestoque.com/?l={$cnpjString}&v=2";

        return $url; // Retorna a URL ou faz algo com ela, como redirecionar
    }
    
    public function limparCNPJ($cnpj) {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }
    
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
        $listings = Listing::with('rListingBrand', 'rListingLocation','user')                
                ->where('listing_status', 'Active')
                ->where('is_featured', 'Yes')
                ->inRandomOrder()
                ->get();
        
        /*->orderBy('id', 'desc')*/

        $total_estoque = Listing::count();
        return view('front.index', compact('adv_home_data', 'page_home_items',
                        'orderwise_listing_brands', 'orderwise_listing_locations', 'listings',
                        'listing_brands', 'listing_locations', 'testimonials', 'page_listing_item', 'user_data',
                        'blog', 'blog_items', 'blog_items_no_pagi', 'categories', 'banners', 'total_estoque'));
    }

    public function getBanners() {
        $banners = Banner::orderBy('id', 'asc')->get();

        return response()->json([
                    'banners' => $banners->map(function ($banner) {
                        $baseName = pathinfo($banner->image, PATHINFO_FILENAME);
                        return [
                            'url' => asset('uploads/banner_photos/' . $banner->image),
                            'url_768w' => asset('uploads/banner_photos/' . $baseName . '-768.webp'),
                            'url_480w' => asset('uploads/banner_photos/' . $baseName . '-480.webp'),
                            'largura' => $banner->largura,
                            'altura' => $banner->altura,
                            'largura_768' => $banner->largura_768,
                            'altura_768' => $banner->altura_768,
                            'largura_480' => $banner->largura_480,
                            'altura_480' => $banner->altura_480,
                        ];
                    })
        ]);
    }

    public function teste() {

        $manager = new ImageManager(new Driver());

// read gif image
        $image = $manager->read(public_path('images/audi.jpg'));
        $outputPath = public_path('images/audi.webp');

// encod jpeg data
        $encoded = $image->toWebp(75)->save($outputPath);

        /* $image = ImageManager::imagick()->read(public_path('images/audi.jpg'));
          //$resizeHeight = $image->resize(height: 200);

          $outputPath = public_path('images/audi.webp');

          $resizeWidth = $image->resizeDown(width: 300)->save();
         */

        $serial = unserialize('a:6:{s:5:"width";i:1900;s:6:"height";i:600;s:4:"file";s:18:"2025/05/banner.jpg";s:8:"filesize";i:321893;s:5:"sizes";a:5:{s:6:"medium";a:5:{s:4:"file";s:17:"banner-300x95.jpg";s:5:"width";i:300;s:6:"height";i:95;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:11318;}s:5:"large";a:5:{s:4:"file";s:19:"banner-1024x323.jpg";s:5:"width";i:1024;s:6:"height";i:323;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:79866;}s:9:"thumbnail";a:5:{s:4:"file";s:18:"banner-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:9481;}s:12:"medium_large";a:5:{s:4:"file";s:18:"banner-768x243.jpg";s:5:"width";i:768;s:6:"height";i:243;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:50633;}s:9:"1536x1536";a:5:{s:4:"file";s:19:"banner-1536x485.jpg";s:5:"width";i:1536;s:6:"height";i:485;s:9:"mime-type";s:10:"image/jpeg";s:8:"filesize";i:147804;}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}');

        /* $manager = new ImageManager(new Driver());


          $image = $manager->read(public_path('images/audi.jpg'));

          // encod jpeg data
          $encoded = $image->toWebp(60); */


        echo '<pre>';
        //var_dump($resizeWidth);
        var_dump($encoded);
        echo '</pre>';
    }
}
