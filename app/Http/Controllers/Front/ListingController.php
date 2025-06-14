<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ListingPageMessage;
use App\Mail\ListingPageReport;
use App\Models\EmailTemplate;
use App\Models\GeneralSetting;
use App\Models\Listing;
use App\Models\Modelo;
use App\Models\Versao;
use App\Models\App_Cidades;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingAmenity;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\ListingPhoto;
use App\Models\ListingSocialItem;
use App\Models\ListingVideo;
use App\Models\Amenity;
use App\Models\PageHomeItem;
use App\Models\PageListingBrandItem;
use App\Models\PageListingItem;
use App\Models\PageListingLocationItem;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use SimpleXMLElement;
use Exception;

class ListingController extends Controller {

    public function index() {
        abort(404);
    }

    public function detail($id = false, $slug = false) {
        $z_flow_client = env('Z_FLOW_CLIENT', 'vila-dos-carros');
        $z_flow_from = env('Z_FLOW_FROM', 'vila-dos-carros');
        $z_flow_elem = env('Z_FLOW_ELEM', 'zflow-container');
        $z_flow_vehicleSellerDocument = env('Z_FLOW_VEHICLESELLERDOCUMENT', '13467746000151');

        $g_setting = GeneralSetting::where('id', 1)->first();
        $detail = Listing::with('rListingLocation', 'rListingBrand','user')
                ->where('id', $id)
                ->where('listing_slug', $slug)
                ->first();

        $detail_phone = '';
        if (!empty($detail->listing_phone) && $detail->listing_phone != '') {
            $clean_phone = $this->limpaCPF_CNPJ($detail->listing_phone);
            if (strlen($clean_phone) == 10) {
                $detail_phone = $this->mask($clean_phone, '(##) ####-####');
            } elseif (strlen($clean_phone) == 11) {
                $detail_phone = $this->mask($clean_phone, '(##) #####-####');
            }
        }
        $listing_social_items = ListingSocialItem::where('listing_id', $detail->id)->get();
        $listing_photos = ListingPhoto::where('listing_id', $detail->id)->get();
        $listing_videos = ListingVideo::where('listing_id', $detail->id)->get();
        $listing_amenities = ListingAmenity::where('listing_id', $detail->id)->get();
        $listing_additional_features = ListingAdditionalFeature::where('listing_id', $detail->id)->get();
        $listing_brands = ListingBrand::orderBy('listing_brand_name', 'asc')->get();
        $listing_locations = ListingLocation::orderBy('listing_location_name', 'asc')->get();

        $reviews = Review::where('listing_id', $detail->id)
                ->orderBy('id', 'asc')
                ->get();

        // Getting overall rating
        if ($reviews->isEmpty()) {
            $overall_rating = 0;
        } else {
            $total_number = 0;
            $count = 0;
            foreach ($reviews as $item) {
                $count++;
                $total_number = $total_number + $item->rating;
            }
            $overall_rating = $total_number / $count;
            if ($overall_rating > 0 && $overall_rating <= 1) {
                $overall_rating = 1;
            } elseif ($overall_rating > 1 && $overall_rating <= 1.5) {
                $overall_rating = 1.5;
            } elseif ($overall_rating > 1.5 && $overall_rating <= 2) {
                $overall_rating = 2;
            } elseif ($overall_rating > 2 && $overall_rating <= 2.5) {
                $overall_rating = 2.5;
            } elseif ($overall_rating > 2.5 && $overall_rating <= 3) {
                $overall_rating = 3;
            } elseif ($overall_rating > 3 && $overall_rating <= 3.5) {
                $overall_rating = 3.5;
            } elseif ($overall_rating > 3.5 && $overall_rating <= 4) {
                $overall_rating = 4;
            } elseif ($overall_rating > 4 && $overall_rating <= 4.5) {
                $overall_rating = 4.5;
            } elseif ($overall_rating > 4.5 && $overall_rating <= 5) {
                $overall_rating = 5;
            }
        }

        if ($detail->user_id == 0) {
            $agent_detail = Admin::where('id', $detail->admin_id)->first();
        } elseif ($detail->admin_id == 0) {
            $agent_detail = User::where('id', $detail->user_id)->first();
        }
        $agent_detail_phone = '';
        $listing_phone = $this->limpaCPF_CNPJ($detail->listing_phone);
        $agent_phone = $this->limpaCPF_CNPJ($agent_detail->phone);

        if ($agent_phone != '') {
            if (strlen($agent_phone) == 10) {
                $agent_detail_phone = $this->mask($agent_phone, '(##) ####-####');
            }
            if (strlen($agent_phone) == 11) {
                $agent_detail_phone = $this->mask($agent_phone, '(##) #####-####');
            }
        } else {
            if (strlen($listing_phone) == 10) {
                $agent_detail_phone = $this->mask($listing_phone, '(##) ####-####');
            }
            if (strlen($listing_phone) == 11) {
                $agent_detail_phone = $this->mask($listing_phone, '(##) #####-####');
            }
        }

        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }

        // If he already given review for this item
        $already_given = 0;
        $already_given = Review::where('listing_id', $detail->id)
                ->where('agent_id', $current_auth_user_id)
                ->where('agent_type', 'Customer')
                ->count();

        $all_amenities = Amenity::orderBy('id', 'asc')->get();
        $kilometragem = (int) $detail->listing_mileage;
        if ($kilometragem <= 40000) {
            $km = number_format($kilometragem, 0, '', '.');
        } else {
            $km = 'Não disponível';
        }

        $address = '';

        if (!empty($detail->canal) && $detail->canal == 'website') {
            if (!empty($detail->cep)) {
                $endereco = null; //$this->getEnderecoByCep($detail->cep);
            }
            $uf = $endereco->uf;
            $city = $endereco->localidade;
            $logradouro = $endereco->logradouro;
        }

        if (!empty($detail->canal) && $detail->canal == 'dsautoestoque') {
            $jsonDecode = json_decode($detail->listing_locations);

            $endereco = null; //$this->getEnderecoByCep($jsonDecode->endereco->cep);

            $uf = trim($jsonDecode->endereco->uf);
            $city = isset($jsonDecode->endereco->cidade) && is_string($jsonDecode->endereco->cidade) ? trim($jsonDecode->endereco->cidade) : '';
            if (empty($uf) || $uf == '0') {
                if (isset($endereco) && !empty($endereco->uf)) {
                    $uf = $endereco->uf;
                }
            }
            if (empty($city)) {
                if ($endereco) {
                    $city = $endereco->localidade;
                } elseif ($detail->rListingLocation->listing_location_name != '') {
                    $city = $detail->rListingLocation->listing_location_name;
                } else {
                    $city = $jsonDecode->endereco->cidade;
                }
            }
            if ($endereco) {
                $logradouro = $endereco->logradouro;
            } else {
                $logradouro = (isset($jsonDecode->endereco->logradouro) && is_string($jsonDecode->endereco->logradouro) ? $jsonDecode->endereco->logradouro : '');
            }
        }

        if ($detail->rListingLocation->listing_location_slug == 'praia-grande') {
            $address = trim('Av. Pres. Kennedy, 3113 - Aviação, Praia Grande - SP, 11702-480');
        }
        if ($detail->rListingLocation->listing_location_slug == 'santos') {
            $address = trim('Av. Washington Luis, 238, Santos, SP, 11050-201');
        }
        return view('front.listing_detail', compact('detail', 'g_setting', 'listing_social_items', 'listing_photos', 'listing_videos', 'listing_amenities', 'listing_additional_features', 'listing_brands', 'listing_locations', 'agent_detail', 'reviews', 'current_auth_user_id', 'already_given', 'overall_rating', 'all_amenities', 'z_flow_client', 'z_flow_from', 'z_flow_elem', 'z_flow_vehicleSellerDocument', 'km', 'user_data', 'address', 'detail_phone', 'agent_detail_phone', 'uf', 'city'));
    }

    public function brand_all() {
        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }
        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_brand_page_data = PageListingBrandItem::where('id', 1)->first();
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
        return view('front.listing_brands', compact('g_setting', 'listing_brand_page_data', 'orderwise_listing_brands', 'user_data'));
    }

    public function brand_detail($slug) {
        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }
        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_brand_page_data = PageListingBrandItem::where('id', 1)->first();
        $listing_brand_detail = ListingBrand::where('listing_brand_slug', $slug)->first();
        if (!$listing_brand_detail || Listing::where('listing_brand_id', $listing_brand_detail->id)->count() === 0) {
            abort(404, 'Marca não encontrada ou sem veículos.');
        }
        $listing_items = Listing::with('rListingBrand', 'rListingLocation')->where('listing_brand_id', $listing_brand_detail->id)->paginate(15);
        return view('front.listing_brand_detail', compact('g_setting', 'listing_brand_detail', 'listing_items', 'listing_brand_page_data', 'user_data'));
    }

    public function location_all() {
        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }
        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_location_page_data = PageListingLocationItem::where('id', 1)->first();
        $orderwise_listing_locations = DB::select('SELECT *
                        FROM listing_locations as r1
                        LEFT JOIN (SELECT listing_location_id, count(*) as total
                            FROM listings as l
                            JOIN listing_brands as lc
                            ON l.listing_location_id = lc.id
                            GROUP BY listing_location_id
                            ORDER BY total DESC) as r2
                        ON r1.id = r2.listing_location_id
                        ORDER BY r2.total DESC');

        return view('front.listing_locations', compact('g_setting', 'listing_location_page_data', 'orderwise_listing_locations', 'user_data'));
    }

    public function location_detail($slug = false) {

        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }
        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_location_page_data = PageListingLocationItem::where('id', 1)->first();
        $listing_location_detail = ListingLocation::where('listing_location_slug', $slug)->first();
        if (!$listing_location_detail || Listing::where('listing_location_id', $listing_location_detail->id)->count() === 0) {
            abort(404, 'Cidade não encontrada ou sem veículos.');
        }

        $listing_items = Listing::with('rListingBrand', 'rListingLocation')->where('listing_location_id', $listing_location_detail->id)->paginate(15);

        return view('front.listing_location_detail', compact('g_setting', 'listing_location_detail', 'listing_items', 'listing_location_page_data', 'user_data'));
    }

    public function agent_detail($type, $identifier) {
        $current_auth_user_id = 0;
        $user_data = null;

        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }

        $g_setting = GeneralSetting::where('id', 1)->first();

        $agent_detail = null;
        $all_listings = collect();

        if ($type === 'admin') {
            $agent_detail = is_numeric($identifier) ? Admin::find($identifier) : Admin::find($identifier);

            if ($agent_detail) {
                $all_listings = Listing::with('rListingBrand', 'rListingLocation','user')
                        ->where('admin_id', $agent_detail->id)
                        ->where('listing_status', 'Active')
                        ->get();
            }
        } else {
            $agent_detail = is_numeric($identifier) ? User::find($identifier) : User::where('slug_user', $identifier)->first();

            if ($agent_detail) {
                $all_listings = Listing::with('rListingBrand', 'rListingLocation','user')
                        ->where('user_id', $agent_detail->id)
                        ->where('listing_status', 'Active')
                        ->get();
            }
        }

        if (!$agent_detail) {
            abort(404, 'Agente não encontrado.');
        }

        $total_listings = $all_listings->count() ?? 0;

        return view('front.listing_agent_detail', compact(
                        'g_setting', 'agent_detail', 'all_listings', 'user_data', 'current_auth_user_id', 'total_listings'
                ));

        /* echo $id;die;
          if (is_numeric($id)) {
          $agent_detail = User::where('id', $id)->first();
          if ($agent_detail) {
          return redirect()->route('front_listing_agent_detail', ['user', $agent_detail->slug_user]);
          }
          } */


        /* $g_setting = GeneralSetting::where('id', 1)->first();
          $total_listings = 0;
          if ($type == 'admin') {
          $agent_detail = Admin::where('id', $id)->first();
          $all_listings = Listing::with('rListingBrand', 'rListingLocation')
          ->where('admin_id', $id)
          ->where('listing_status', 'Active')
          ->get();
          } else {
          $agent_detail = User::where('id', $id)->orWhere('slug_user', $id)->first();
          $all_listings = Listing::with('rListingBrand', 'rListingLocation')
          ->where('user_id', $agent_detail->id)
          ->where('listing_status', 'Active')
          ->get();
          }
          $total_listings = count($all_listings);

          return view('front.listing_agent_detail', compact('g_setting', 'agent_detail', 'all_listings', 'user_data', 'current_auth_user_id', 'total_listings')); */
    }

    public function listing_result(Request $request) {
        $current_auth_user_id = 0;
        $user_data = null;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
            $user_data = Auth::user();
        }

        $listings = Listing::with('user');

        if ($request->amenity) {
            $listings = $listings->whereHas('listingAmenities', function ($query) use ($request) {
                $query->whereIn('amenity_id', $request->amenity);
            });
        }

        if ($request->additional) {
            $listings = $listings->whereHas('listingAdditionals', function ($query) use ($request) {
                $query->whereIn('additional_id', $request->additional);
            });
        }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_page_data = PageListingItem::where('id', 1)->first();

        $amenities = Amenity::select('amenities.id AS amenities_id', 'amenities.amenity_name', \DB::raw('COUNT(listing_amenities.listing_id) as listing_count'))
                ->join('listing_amenities', 'amenities.id', '=', 'listing_amenities.amenity_id')
                ->join('listings', 'listings.id', '=', 'listing_amenities.listing_id')
                ->where('listings.listing_status', 'Active') // Inclui apenas listings ativos, se aplicável
                ->groupBy('amenities.id', 'amenities.amenity_name')
                ->orderBy('amenities.amenity_name', 'asc')
                ->get();
        $additionals = \App\Models\Additional::select('additionals.id AS additionals_id', 'additionals.additional_name', \DB::raw('COUNT(listings.id) as listing_count'))
                ->join('listing_additional_features', 'additionals.id', '=', 'listing_additional_features.additional_id')
                ->join('listings', 'listings.id', '=', 'listing_additional_features.listing_id')
                ->where('listings.listing_status', 'Active') // Inclui apenas listings ativos, se aplicável
                ->groupBy('additionals.id', 'additionals.additional_name')
                ->orderBy('additionals.additional_name', 'asc')
                ->get();

        $page_home_items = PageHomeItem::where('id', 1)->first();

        $brandCounts = Listing::select('listing_brand_id', \DB::raw('count(*) as total'))
                ->groupBy('listing_brand_id')
                ->pluck('total', 'listing_brand_id');

        $listing_brands = ListingBrand::whereIn('id', function ($query) {
                    $query->select('listing_brand_id')
                            ->from('listings')
                            ->groupBy('listing_brand_id');
                })
                ->get()
                // Ordenar pelo total de listagens (de forma decrescente)
                ->sortByDesc(function ($brand) use ($brandCounts) {
                    return $brandCounts[$brand->id] ?? 0;
                });

        $listing_locations = ListingLocation::all();

        $locationCounts = Listing::select('listing_location_id', \DB::raw('count(*) as total'))
                ->groupBy('listing_location_id')
                ->pluck('total', 'listing_location_id');

        // Breaking Urls
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link_len = strlen($actual_link);

        $first_part = url()->current();
        $first_part_len = strlen($first_part);

        $all_brand = [];
        $all_additional = [];
        $all_location = [];
        $all_amenity = [];

        $aa = substr($actual_link, ($first_part_len + 1), ($actual_link_len - 1));
        $arr = explode('&', $aa);

        $mileageRange = Listing::selectRaw('MAX(listing_mileage) as max_mileage, MIN(listing_mileage) as min_mileage')->first();
        $maxMileage = $mileageRange->max_mileage;
        $minMileage = $mileageRange->min_mileage;
        $km_min = $request->input('km_min', $minMileage);
        $km_max = $request->input('km_max', $maxMileage);

        $priceRange = Listing::selectRaw('MAX(listing_price) as max_price, MIN(listing_price) as min_price')->first();
        $maxPrice = $priceRange->max_price;
        $minPrice = $priceRange->min_price;
        $price_min = $request->input('price_min', $minPrice);
        $price_max = $request->input('price_max', $maxPrice);

        $anoRange = Listing::selectRaw('MAX(listing_model_year) as max_ano, MIN(listing_model_year) as min_ano')->first();
        $maxAno = $anoRange->max_ano;
        $minAno = $anoRange->min_ano;
        $ano_min = $request->input('ano_min', $minAno);
        $ano_max = $request->input('ano_max', $maxAno);

        if ($request->location) {
            $location_arr = array_map('intval', $request->location);
            $listings->whereIn('listing_location_id', $location_arr);
        }
        if ($request->cambio) {
            $cambio_arr = array_map('intval', $request->cambio);
            $listings->whereIn('listing_transmission_id', $cambio_arr);
        }
        if ($request->cor) {
            $cor_arr = array_map('intval', $request->cor);
            $listings->whereIn('listing_exterior_color_id', $cor_arr);
        }
        if ($request->combustivel) {
            $combustivel_arr = array_map('intval', $request->combustivel);
            $listings->whereIn('listing_fuel_type_id', $combustivel_arr);
        }
        if ($request->carroceria) {
            $carroceria_arr = array_map('strval', $request->carroceria);
            $listings->whereIn('listing_body', $carroceria_arr);
        }

        if ($request->brand && is_array($request->brand)) {
            $brand_arr = array_map('intval', $request->brand);
            $listings->whereIn('listing_brand_id', $brand_arr);
        }

        if ($request->modelo && is_array($request->modelo)) {
            $modelo_arr = array_map('intval', $request->modelo);
            $listings->whereIn('listing_modelo_id', $modelo_arr);
        }

        if ($request->listing_type) {
            $listings->where('listing_type', $request->listing_type);
        }



        if ($request->text) {
            $listings->where(function ($query) use ($request) {
                $query->where('listing_name', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_address', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_locations', 'LIKE', '%' . $request->text . '%');
            });
        }
        if (!empty($km_min)) {
            $km_min = $km_min;
        }
        if (!empty($km_max)) {
            $km_max = $km_max;
        }
        if (!empty($price_min)) {
            $price_min = $price_min;
        }
        if (!empty($price_max)) {
            $price_max = $price_max;
        }
        if (!empty($ano_min)) {
            $ano_min = $ano_min;
        }
        if (!empty($ano_max)) {
            $ano_max = $ano_max;
        }
        if (!empty($km_min) && !empty($km_max)) {
            $listings->whereBetween('listing_mileage', [$km_min, $km_max]);
        }
        if (!empty($price_min) && !empty($price_max)) {
            $listings->whereBetween('listing_price', [$price_min, $price_max]);
        }
        if (!empty($ano_min) && !empty($ano_max)) {
            $listings->whereBetween('listing_model_year', [$ano_min, $ano_max]);
        }

        $listings->where('listing_status', 'Active');

        if (!empty($request->order)) {
            switch ($request->order) {
                case 'price_asc':
                    $listings->orderBy('listing_price', 'ASC');
                    break;
                case 'price_desc':
                    $listings->orderBy('listing_price', 'DESC');
                    break;
                case 'km_asc':
                    $listings->orderBy('listing_mileage', 'ASC');
                    break;
                case 'km_desc':
                    $listings->orderBy('listing_mileage', 'DESC');
                    break;
                case 'relevancia':
                    if ($request->text) {
                        $listings->orderByRaw("
                    (CASE 
                        WHEN listing_name LIKE ? THEN 3
                        WHEN listing_address LIKE ? THEN 2
                        WHEN listing_locations LIKE ? THEN 1
                        ELSE 0 
                    END) DESC",
                                ["%{$request->text}%", "%{$request->text}%", "%{$request->text}%"]);
                    } else {
                        $listings->orderBy('id', 'desc'); // Se não houver termo de busca, ordena pelo mais recente
                    }
                    break;
                case 'random':
                    $listings->inRandomOrder();
                    break;
                default:
                    //$listings->orderBy('listing_price', 'DESC');
                    $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                            ->orderBy('listing_price', 'DESC')->inRandomOrder();
                    //$listings-;
                    // $listings->orderBy('id', 'desc');
                    break;
            }
        } else {
            //$listings->inRandomOrder();
            //$listings->orderBy('listing_price', 'DESC');
            //$listings->orderBy('id', 'desc');
            /* $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
              ->orderBy('id', 'DESC'); */
            $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                    ->orderBy('listing_price', 'DESC')->inRandomOrder();
        }

        $total_registros = $listings->count();

        //$quantidade_para_exibir = 30;
        $quantidade_para_exibir = min($total_registros, 30);
        $listings = $listings->paginate($quantidade_para_exibir)->appends($request->all());

        $listing_type_counts = Listing::select('listing_type', \DB::raw('count(*) as total'))
                ->where('listing_status', 'Active') // Filtra apenas os listings ativos, se necessário
                ->groupBy('listing_type')
                ->pluck('total', 'listing_type');

        $listings_cambio = Listing::select('listing_transmission', 'listing_transmission_id', DB::raw('count(*) as total'))
                ->groupBy('listing_transmission', 'listing_transmission_id')
                ->orderBy('listing_transmission', 'asc')
                ->get();
        $listings_cor = Listing::select('listing_exterior_color', 'listing_exterior_color_id', DB::raw('count(*) as total'))
                ->groupBy('listing_exterior_color', 'listing_exterior_color_id')
                ->orderBy('listing_exterior_color', 'asc')
                ->get();
        $listings_combustivel = Listing::select('listing_fuel_type', 'listing_fuel_type_id', DB::raw('count(*) as total'))
                ->groupBy('listing_fuel_type', 'listing_fuel_type_id')
                ->orderBy('listing_fuel_type', 'asc')
                ->get();
        $listings_carroceria = Listing::select('listing_body', DB::raw('count(*) as total'))
                ->groupBy('listing_body')
                ->orderBy('listing_body', 'asc')
                ->get();

        return view('front.listing_result', compact('g_setting', 'listing_page_data', 'listing_brands', 'listing_locations', 'amenities', 'all_brand', 'all_location', 'all_amenity', 'listings', 'quantidade_para_exibir', 'total_registros', 'page_home_items', 'listings_cambio', 'user_data', 'locationCounts', 'brandCounts', 'listing_type_counts', 'minMileage', 'maxMileage', 'minPrice', 'maxPrice', 'minAno', 'maxAno', 'listings_cor', 'listings_combustivel', 'additionals', 'listings_carroceria'));
    }

    public function search_listing(Request $request) {
        $current_auth_user_id = Auth::user() ? Auth::user()->id : 0;
        $user_data = Auth::user();

        $listings = Listing::with('user');

        if ($request->amenity) {
            $listings = $listings->whereHas('listingAmenities', function ($query) use ($request) {
                $query->whereIn('amenity_id', $request->amenity);
            });
        }

        if ($request->additional) {
            $listings = $listings->whereHas('listingAdditionals', function ($query) use ($request) {
                $query->whereIn('additional_id', $request->additional);
            });
        }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing_page_data = PageListingItem::where('id', 1)->first();

        $amenities = Amenity::select('amenities.id', 'amenities.amenity_name', \DB::raw('COUNT(listings.id) as listing_count'))
                ->join('listing_amenities', 'amenities.id', '=', 'listing_amenities.amenity_id')
                ->join('listings', 'listings.id', '=', 'listing_amenities.listing_id')
                ->where('listings.listing_status', 'Active') // Inclui apenas listings ativos, se aplicável
                ->groupBy('amenities.id', 'amenities.amenity_name')
                ->orderBy('amenities.amenity_name', 'asc')
                ->get();
        $additionals = \App\Models\Additional::select('additionals.id', 'additionals.additional_name', \DB::raw('COUNT(listings.id) as listing_count'))
                ->join('listing_additional_features', 'additionals.id', '=', 'listing_additional_features.additional_id')
                ->join('listings', 'listings.id', '=', 'listing_additional_features.listing_id')
                ->where('listings.listing_status', 'Active') // Inclui apenas listings ativos, se aplicável
                ->groupBy('additionals.id', 'additionals.additional_name')
                ->orderBy('additionals.additional_name', 'asc')
                ->get();

        $page_home_items = PageHomeItem::where('id', 1)->first();

        $brandCounts = Listing::select('listing_brand_id', \DB::raw('count(*) as total'))
                ->groupBy('listing_brand_id')
                ->pluck('total', 'listing_brand_id');

        $listing_brands = ListingBrand::whereIn('id', function ($query) {
                    $query->select('listing_brand_id')
                            ->from('listings')
                            ->groupBy('listing_brand_id');
                })
                ->get()
                ->sortByDesc(function ($brand) use ($brandCounts) {
                    return $brandCounts[$brand->id] ?? 0;
                });

        $listing_locations = ListingLocation::all();

        $locationCounts = Listing::select('listing_location_id', \DB::raw('count(*) as total'))
                ->groupBy('listing_location_id')
                ->pluck('total', 'listing_location_id');

        $mileageRange = Listing::selectRaw('MAX(listing_mileage) as max_mileage, MIN(listing_mileage) as min_mileage')->first();
        $maxMileage = $mileageRange->max_mileage;
        $minMileage = $mileageRange->min_mileage;
        $km_min = $request->input('km_min', $minMileage);
        $km_max = $request->input('km_max', $maxMileage);

        $priceRange = Listing::selectRaw('MAX(listing_price) as max_price, MIN(listing_price) as min_price')->first();
        $maxPrice = $priceRange->max_price;
        $minPrice = $priceRange->min_price;
        $price_min = $request->input('price_min', $minPrice);
        $price_max = $request->input('price_max', $maxPrice);

        $anoRange = Listing::selectRaw('MAX(listing_model_year) as max_ano, MIN(listing_model_year) as min_ano')->first();
        $maxAno = $anoRange->max_ano;
        $minAno = $anoRange->min_ano;
        $ano_min = $request->input('ano_min', $minAno);
        $ano_max = $request->input('ano_max', $maxAno);

        // Breaking Urls
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link_len = strlen($actual_link);

        $first_part = url()->current();
        $first_part_len = strlen($first_part);

        $all_brand = [];
        $all_location = [];
        $all_amenity = [];
        $all_additional = [];

        $aa = substr($actual_link, ($first_part_len + 1), ($actual_link_len - 1));
        $arr = explode('&', $aa);

        if ($request->location) {
            $location_arr = array_map('intval', $request->location);
            $listings->whereIn('listing_location_id', $location_arr);
        }
        if ($request->cambio) {
            $cambio_arr = array_map('intval', $request->cambio);
            $listings->whereIn('listing_transmission_id', $cambio_arr);
        }
        if ($request->cor) {
            $cor_arr = array_map('intval', $request->cor);
            $listings->whereIn('listing_exterior_color_id', $cor_arr);
        }
        if ($request->combustivel) {
            $combustivel_arr = array_map('intval', $request->combustivel);
            $listings->whereIn('listing_fuel_type_id', $combustivel_arr);
        }
        if ($request->carroceria) {
            $carroceria_arr = array_map('strval', $request->carroceria);
            $listings->whereIn('listing_body', $carroceria_arr);
        }

        if ($request->brand && is_array($request->brand)) {
            $brand_arr = array_map('intval', $request->brand);
            $listings->whereIn('listing_brand_id', $brand_arr);
        }

        if ($request->modelo && is_array($request->modelo)) {
            $modelo_arr = array_map('intval', $request->modelo);
            $listings->whereIn('listing_modelo_id', $modelo_arr);
        }

        if ($request->listing_type) {
            $listings->where('listing_type', $request->listing_type);
        }


        if ($request->text) {
            $listings->where(function ($query) use ($request) {
                $query->where('listing_name', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_address', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_locations', 'LIKE', '%' . $request->text . '%');
            });
        }
        if (!empty($km_min)) {
            $km_min = $km_min;
        }
        if (!empty($km_max)) {
            $km_max = $km_max;
        }
        if (!empty($price_min)) {
            $price_min = $price_min;
        }
        if (!empty($price_max)) {
            $price_max = $price_max;
        }
        if (!empty($ano_min)) {
            $ano_min = $ano_min;
        }
        if (!empty($ano_max)) {
            $ano_max = $ano_max;
        }
        if (!empty($km_min) && !empty($km_max)) {
            $listings->whereBetween('listing_mileage', [$km_min, $km_max]);
        }
        if (!empty($price_min) && !empty($price_max)) {
            $listings->whereBetween('listing_price', [$price_min, $price_max]);
        }
        if (!empty($ano_min) && !empty($ano_max)) {
            $listings->whereBetween('listing_model_year', [$ano_min, $ano_max]);
        }

        $listings->where('listing_status', 'Active');

        if (!empty($request->order)) {
            switch ($request->order) {
                case 'price_asc':
                    $listings->orderBy('listing_price', 'ASC');
                    break;
                case 'price_desc':
                    $listings->orderBy('listing_price', 'DESC');
                    break;
                case 'km_asc':
                    $listings->orderBy('listing_mileage', 'ASC');
                    break;
                case 'km_desc':
                    $listings->orderBy('listing_mileage', 'DESC');
                    break;
                case 'relevancia':
                    if ($request->text) {
                        $listings->orderByRaw("
                    (CASE 
                        WHEN listing_name LIKE ? THEN 3
                        WHEN listing_address LIKE ? THEN 2
                        WHEN listing_locations LIKE ? THEN 1
                        ELSE 0 
                    END) DESC",
                                ["%{$request->text}%", "%{$request->text}%", "%{$request->text}%"]);
                    } else {
                        $listings->orderBy('id', 'desc'); // Se não houver termo de busca, ordena pelo mais recente
                    }
                    break;
                case 'random':
                    $listings->inRandomOrder();
                    break;
                default:
                    //$listings->inRandomOrder();
                    /* $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                      ->orderBy('id', 'DESC'); */
                    $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                            ->orderBy('listing_price', 'DESC')->inRandomOrder();
                    //$listings->orderBy('id', 'desc');
                    break;
            }
        } else {
            //$listings->inRandomOrder();
            //$listings->orderBy('listing_price', 'DESC');
            //$listings->orderBy('id', 'desc');
            /* $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
              ->orderBy('id', 'DESC'); */
            $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                    ->orderBy('listing_price', 'DESC')->inRandomOrder();
        }

        $total_registros = $listings->count();

        //$quantidade_para_exibir = 30;
        $quantidade_para_exibir = min($total_registros, 30);
        $listings = $listings->paginate($quantidade_para_exibir)->appends($request->all());

        $listing_type_counts = Listing::select('listing_type', \DB::raw('count(*) as total'))
                ->where('listing_status', 'Active') // Filtra apenas os listings ativos, se necessário
                ->groupBy('listing_type')
                ->pluck('total', 'listing_type');

        $listings_cambio = Listing::select('listing_transmission', 'listing_transmission_id', DB::raw('count(*) as total'))
                ->groupBy('listing_transmission', 'listing_transmission_id')
                ->orderBy('listing_transmission', 'asc')
                ->get();
        $listings_cor = Listing::select('listing_exterior_color', 'listing_exterior_color_id', DB::raw('count(*) as total'))
                ->groupBy('listing_exterior_color', 'listing_exterior_color_id')
                ->orderBy('listing_exterior_color', 'asc')
                ->get();
        $listings_combustivel = Listing::select('listing_fuel_type', 'listing_fuel_type_id', DB::raw('count(*) as total'))
                ->groupBy('listing_fuel_type', 'listing_fuel_type_id')
                ->orderBy('listing_fuel_type', 'asc')
                ->get();
        $listings_carroceria = Listing::select('listing_body', DB::raw('count(*) as total'))
                ->groupBy('listing_body')
                ->orderBy('listing_body', 'asc')
                ->get();

        return view('front.listing_result', compact('g_setting', 'listing_page_data', 'listing_brands', 'listing_locations', 'amenities', 'all_brand', 'all_location', 'all_amenity', 'listings', 'quantidade_para_exibir', 'total_registros', 'page_home_items', 'listings_cambio', 'user_data', 'locationCounts', 'listings_cor', 'listings_combustivel', 'minAno', 'maxAno', 'minPrice', 'maxPrice', 'minMileage', 'maxMileage', 'listing_type_counts', 'brandCounts', 'additionals', 'listings_carroceria'));
    }

    public function search_listing_result(Request $request) {
        $current_auth_user_id = Auth::user() ? Auth::user()->id : 0;
        $user_data = Auth::user();

        $listings = Listing::with('user');

        if ($request->amenity) {
            $listings = $listings->whereHas('listingAmenities', function ($query) use ($request) {
                $query->whereIn('amenity_id', $request->amenity);
            });
        }

        if ($request->additional) {
            $listings = $listings->whereHas('listingAdditionals', function ($query) use ($request) {
                $query->whereIn('additional_id', $request->additional);
            });
        }

        /* kilometragem */
        $mileageRange = Listing::selectRaw('MAX(listing_mileage) as max_mileage, MIN(listing_mileage) as min_mileage')->first();
        $maxMileage = $mileageRange->max_mileage;
        $minMileage = $mileageRange->min_mileage;
        $km_min = $request->input('km_min', $minMileage);
        $km_max = $request->input('km_max', $maxMileage);
        /* kilometragem */

        $priceRange = Listing::selectRaw('MAX(listing_price) as max_price, MIN(listing_price) as min_price')->first();
        $maxPrice = $priceRange->max_price;
        $minPrice = $priceRange->min_price;
        $price_min = $request->input('price_min', $minPrice);
        $price_max = $request->input('price_max', $maxPrice);

        $anoRange = Listing::selectRaw('MAX(listing_model_year) as max_ano, MIN(listing_model_year) as min_ano')->first();
        $maxAno = $anoRange->max_ano;
        $minAno = $anoRange->min_ano;
        $ano_min = $request->input('ano_min', $minAno);
        $ano_max = $request->input('ano_max', $maxAno);

        if ($request->location) {
            $location_arr = array_map('intval', $request->location);
            $listings->whereIn('listing_location_id', $location_arr);
        }
        if ($request->cambio) {
            $cambio_arr = array_map('intval', $request->cambio);
            $listings->whereIn('listing_transmission_id', $cambio_arr);
        }
        if ($request->cor) {
            $cor_arr = array_map('intval', $request->cor);
            $listings->whereIn('listing_exterior_color_id', $cor_arr);
        }
        if ($request->carroceria) {
            $carroceria_arr = array_map('strval', $request->carroceria);
            $listings->whereIn('listing_body', $carroceria_arr);
        }
        if ($request->combustivel) {
            $combustivel_arr = array_map('intval', $request->combustivel);
            $listings->whereIn('listing_fuel_type_id', $combustivel_arr);
        }


        if ($request->brand && is_array($request->brand)) {
            $brand_arr = array_map('intval', $request->brand);
            $listings->whereIn('listing_brand_id', $brand_arr);
        }

        if ($request->modelo && is_array($request->modelo)) {
            $modelo_arr = array_map('intval', $request->modelo);
            $listings->whereIn('listing_modelo_id', $modelo_arr);
        }

        if ($request->listing_type) {
            $listings->where('listing_type', $request->listing_type);
        }



        if ($request->text) {
            $listings->where(function ($query) use ($request) {
                $query->where('listing_name', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_address', 'LIKE', '%' . $request->text . '%')
                        ->orWhere('listing_locations', 'LIKE', '%' . $request->text . '%');
            });
        }
        if (!empty($km_min)) {
            $km_min = $km_min;
        }
        if (!empty($km_max)) {
            $km_max = $km_max;
        }
        if (!empty($price_min)) {
            $price_min = $price_min;
        }
        if (!empty($price_max)) {
            $price_max = $price_max;
        }
        if (!empty($ano_min)) {
            $ano_min = $ano_min;
        }
        if (!empty($ano_max)) {
            $ano_max = $ano_max;
        }
        if (!empty($km_min) && !empty($km_max)) {
            $listings->whereBetween('listing_mileage', [$km_min, $km_max]);
        }
        if (!empty($price_min) && !empty($price_max)) {
            $listings->whereBetween('listing_price', [$price_min, $price_max]);
        }
        if (!empty($ano_min) && !empty($ano_max)) {
            $listings->whereBetween('listing_model_year', [$ano_min, $ano_max]);
        }

        $listings->where('listing_status', 'Active');

        if (!empty($request->order)) {
            switch ($request->order) {
                case 'price_asc':
                    $listings->orderBy('listing_price', 'ASC');
                    break;
                case 'price_desc':
                    $listings->orderBy('listing_price', 'DESC');
                    break;
                case 'km_asc':
                    $listings->orderBy('listing_mileage', 'ASC');
                    break;
                case 'km_desc':
                    $listings->orderBy('listing_mileage', 'DESC');
                    break;
                case 'relevancia':
                    if ($request->text) {
                        $listings->orderByRaw("
                    (CASE 
                        WHEN listing_name LIKE ? THEN 3
                        WHEN listing_address LIKE ? THEN 2
                        WHEN listing_locations LIKE ? THEN 1
                        ELSE 0 
                    END) DESC",
                                ["%{$request->text}%", "%{$request->text}%", "%{$request->text}%"]);
                    } else {
                        $listings->orderBy('id', 'desc'); // Se não houver termo de busca, ordena pelo mais recente
                    }
                    break;
                case 'random':
                    $listings->inRandomOrder();
                    break;
                default:
                    //$listings->inRandomOrder();
                    /* $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                      ->orderBy('id', 'DESC'); */
                    $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                            ->orderBy('listing_price', 'DESC')->inRandomOrder();
                    //$listings->orderBy('id', 'desc'); // Último registro
                    break;
            }
        } else {
            //$listings->inRandomOrder(); 
            //$listings->orderBy('listing_price', 'DESC');
            //$listings->orderBy('id', 'desc');
            /* $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
              ->orderBy('id', 'DESC'); */
            $listings->orderByDesc(DB::raw("is_featured = 'Yes'"))
                    ->orderBy('listing_price', 'DESC')->inRandomOrder();
        }

        /* dd($listings->toSql(), $listings->getBindings());die; */
        $total_registros = $listings->count();

        //$quantidade_para_exibir = 30;
        $quantidade_para_exibir = min($total_registros, 30);
        $listings = $listings->paginate($quantidade_para_exibir)->appends($request->all());

        $listings_cambio = Listing::select('listing_transmission', 'listing_transmission_id', DB::raw('count(*) as total'))
                ->groupBy('listing_transmission', 'listing_transmission_id')
                ->orderBy('listing_transmission', 'asc')
                ->get();

        $listings_cor = Listing::select('listing_exterior_color', 'listing_exterior_color_id', DB::raw('count(*) as total'))
                ->groupBy('listing_exterior_color', 'listing_exterior_color_id')
                ->orderBy('listing_exterior_color', 'asc')
                ->get();

        $listings_combustivel = Listing::select('listing_fuel_type', 'listing_fuel_type_id', DB::raw('count(*) as total'))
                ->groupBy('listing_fuel_type', 'listing_fuel_type_id')
                ->orderBy('listing_fuel_type', 'asc')
                ->get();
        $listings_carroceria = Listing::select('listing_body', DB::raw('count(*) as total'))
                ->groupBy('listing_body')
                ->orderBy('listing_body', 'asc')
                ->get();

        if ($total_registros > $quantidade_para_exibir) {
            $quantidade_para_exibir = $quantidade_para_exibir;
        } else {
            $quantidade_para_exibir = $total_registros;
        }



        if ($listings->isEmpty()) {
            return view('front.ajax_search_listing_nada', compact('total_registros'));
        } else {
            return view('front.ajax_search_listing', compact('listings', 'quantidade_para_exibir', 'total_registros', 'listings_cambio', 'user_data', 'listings_cor', 'listings_combustivel', 'listings_carroceria'));
        }
    }

    public function enviarLeadAjax(Request $request) {
        $apiKey = env('RD_API_KEY');
        // Validação básica
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'mobile_phone' => 'required|string'
        ]);

        // Dados da requisição
        $data = [
            'event_type' => 'CONVERSION',
            'event_family' => 'CDP',
            'payload' => [
                'conversion_identifier' => 'Formulário do site: vila dos carros - financiamentos',
                'email' => $request->email,
                'name' => $request->name,
                'mobile_phone' => $request->mobile_phone
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/conversions?api_key={$apiKey}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'event_type' => 'CONVERSION',
                'event_family' => 'CDP',
                'payload' => [
                    'conversion_identifier' => 'Formulário do site: vila dos carros - financiamentos',
                    'email' => $request->email,
                    'name' => $request->name,
                    'mobile_phone' => $request->mobile_phone
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
            //echo "cURL Error #:" . $err;
        } else {
            return true;
            // echo $response;
        }

        // Envio para a API do RD Station
        /* $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'accept' => 'application/json'
          ])
          ->post('https://api.rd.services/platform/conversions?api_key='.$apiKey,
          $data
          ); */
        /* echo '<pre>';
          var_dump($response);
          echo '</pre>';die; */
        // Verificando a resposta
        /* if ($response->successful()) {
          return response()->json(['message' => 'Lead enviado com sucesso!'], 200);
          } else {
          return response()->json(['error' => 'Erro ao enviar lead.', 'details' => $response->json()], 400);
          } */
    }

    public function enviarLead($dados) {
        $apiKey = env('RD_API_KEY');
        // Dados da requisição
        $data = [
            'event_type' => 'CONVERSION',
            'event_family' => 'CDP',
            'payload' => [
                'conversion_identifier' => $dados['tag'],
                'email' => $dados['email'],
                'name' => $dados['name'],
                'mobile_phone' => $dados['mobile_phone']
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/conversions?api_key={$apiKey}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'event_type' => 'CONVERSION',
                'event_family' => 'CDP',
                'payload' => [
                    'conversion_identifier' => $dados['tag'],
                    'email' => $dados['email'],
                    'name' => $dados['name'],
                    'mobile_phone' => $dados['mobile_phone']
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
            //echo "cURL Error #:" . $err;
        } else {
            return true;
            // echo $response;
        }


        // Envio para a API do RD Station
        /* $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'accept' => 'application/json'
          ])
          ->post('https://api.rd.services/platform/conversions?api_key='.$apiKey,
          $data
          ); */

        // Verificando a resposta
        /* if ($response->successful()) {
          return true;//response()->json(['message' => 'Lead enviado com sucesso!'], 200);
          } else {
          return false;//response()->json(['error' => 'Erro ao enviar lead.', 'details' => $response->json()], 400);
          } */
    }

    public function send_message(Request $request) {
        /* if (env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $g_setting = GeneralSetting::where('id', 1)->first();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
                ], [
            'name.required' => 'Nome é obrigatório',
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID,
            'message.required' => ERR_MESSAGE_REQUIRED
        ]);

        if ($g_setting->google_recaptcha_status == 'Shows') {
            $request->validate([
                'g-recaptcha-response' => 'required'
                    ], [
                'g-recaptcha-response.required' => ERR_RECAPTCHA_REQUIRED
            ]);
        }

        $listing_result = Listing::with('rListingLocation', 'rListingBrand')
                ->where('id', $request->id)
                ->first();
        $listing_name = $request->listing_name;
        $listing_url = '<a href="' . route('front_listing_detail', [$request->id, $request->listing_slug]) . '">' . route('front_listing_detail', [$request->id, $request->listing_slug]) . '</a>';
        $agent_name = $request->agent_name;

        $dados = [
            'tag' => 'Contato do site: Vila dos carros - ' . $agent_name . ' - ' . $listing_name . ' - ' . $listing_url,
            'email' => $request->email,
            'name' => $request->name,
            'mobile_phone' => $request->phone
        ];
        //$this->enviarLead($dados);
        // Send Email
        $email_template_data = EmailTemplate::where('id', 9)->first();
        $subject = $email_template_data->et_subject;
        $message = $email_template_data->et_content;

        $message = str_replace('[[agent_name]]', $agent_name, $message);
        $message = str_replace('[[listing_name]]', $listing_name, $message);
        $message = str_replace('[[listing_url]]', $listing_url, $message);
        $message = str_replace('[[name]]', $request->name, $message);
        $message = str_replace('[[email]]', $request->email, $message);
        $message = str_replace('[[phone]]', $request->phone, $message);
        $message = str_replace('[[message]]', $request->message, $message);

        //Mail::to($request->agent_email)->send(new ListingPageMessage($subject, $message));

        $agent_detail = User::where('id', $listing_result->user_id)->first();

        $assunto = 'Contato do site: Vila dos carros - ' . $agent_name . ' - ' . $listing_name . ' - ' . $listing_url;
        $mensagem = "
                DADOS DO LEAD <br><br>
            Nome: {$request->name} <br>
                Telefone: {$request->phone} <br>
                E-mail: {$request->email} <br>
                        Mensagem: {$message} <br>
                    <br>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Vila dos Carros <viladoscarrosmkt@gmail.com>" . "\r\n";

        $email_enviado2 = mail('viladoscarros@contact2sale.com', $assunto, $mensagem);

        return redirect()->back()->with('success', SUCCESS_MESSAGE_SENT);
    }

    public function report_listing(Request $request) {
        /* if (env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $g_setting = GeneralSetting::where('id', 1)->first();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
                ], [
            'name.required' => ERR_NAME_REQUIRED,
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID,
            'message.required' => ERR_MESSAGE_REQUIRED,
        ]);

        if ($g_setting->google_recaptcha_status == 'Show') {
            $request->validate([
                'g-recaptcha-response' => 'required'
                    ], [
                'g-recaptcha-response.required' => ERR_RECAPTCHA_REQUIRED
            ]);
        }

        $listing_name = $request->listing_name;
        $listing_url = '<a href="' . route('front_listing_detail', [$request->id, $request->listing_slug]) . '">' . route('front_listing_detail', [$request->id, $request->listing_slug]) . '</a>';

        // Send Email
        $email_template_data = EmailTemplate::where('id', 10)->first();
        $subject = $email_template_data->et_subject;
        $message = $email_template_data->et_content;

        $message = str_replace('[[listing_name]]', $listing_name, $message);
        $message = str_replace('[[listing_url]]', $listing_url, $message);
        $message = str_replace('[[name]]', $request->name, $message);
        $message = str_replace('[[email]]', $request->email, $message);
        $message = str_replace('[[phone]]', $request->phone, $message);
        $message = str_replace('[[message]]', $request->message, $message);

        $admin_data = Admin::where('id', 1)->first();

        Mail::to($admin_data->email)->send(new ListingPageReport($subject, $message));

        return redirect()->back()->with('success', SUCCESS_REPORT_SENT);
    }

    public function wishlist_add($id) {
        /* if (env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        if (Auth::user() == null) {
            return redirect()->back()->with('error', ERR_LOGIN_FIRST);
        }

        $check_previous = Wishlist::where('listing_id', $id)->count();
        if ($check_previous > 0) {
            return redirect()->back()->with('error', ERR_ALREADY_TO_WISHLIST);
        }

        $user_data = Auth::user();

        $obj = new Wishlist;
        $obj->user_id = $user_data->id;
        $obj->listing_id = $id;
        $obj->save();

        return redirect()->back()->with('success', SUCCESS_WISHLIST_ADD);
    }

    public function limparCNPJ($cnpj) {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }

    public function montarUrlComCnpjs() {
        $cnpjs = User::where('status', 'Active')->pluck('cnpj')->toArray();
        $cnpjs = array_filter($cnpjs);
        $cnpjs = array_map([$this, 'limparCNPJ'], $cnpjs);
        $cnpjString = implode(',', $cnpjs);
        $url = "https://xml.dsautoestoque.com/?l={$cnpjString}&v=2";
        return $url;
    }

    public function handle() {
        $url = $this->montarUrlComCnpjs();
        $xmlContent = @file_get_contents($url);
        if ($xmlContent === FALSE) {
            return;
        }
        try {
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);
            return $xml;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function remover_veiculos_nao_encontrados_no_xml() {

        $xml = $this->handle();

        if ($xml instanceof SimpleXMLElement && isset($xml->veiculo)) {
            $veiculoAPI_array = array();
            foreach ($xml->veiculo as $veiculo) {
                if (isset($veiculo->id)) {
                    $veiculoAPI_array[] = (int) $veiculo->id;
                }
            }

            if (!empty($veiculoAPI_array)) {
                $list = Listing::all();

                foreach ($list as $row) {
                    $post_veiculo_id = $row->veiculo_id;

                    if (!in_array($post_veiculo_id, $veiculoAPI_array)) {
                        //echo 'O ID do veículo ' . $post_veiculo_id . ' não existe<br>';
                        error_log('O ID do veículo ' . $post_veiculo_id . ' não existe, pode deletar do banco de dados');
                        Listing::where('id', $row->id)->delete();
                    }
                }
            } else {
                //echo 'Nenhum veículo encontrado no XML.<br>';
                error_log('Nenhum veículo encontrado no XML.');
            }
        } else {
            //echo 'Falha ao carregar XML ou estrutura inválida.<br>';
            error_log('Falha ao carregar XML ou estrutura inválida.');
        }
    }

    public function show_phone($id) {
        $phones = Listing::findOrFail($id)->listing_phone;
        $telefone = $this->limparCNPJ($phones);

        if (!empty($telefone)) {
            $phone = '<a class="btn btn-dark" style="color: #ffffff;" href="tel:' . $this->mask($telefone, '(##) ####-####') . '"><i class="fas fa-phone"></i> ' . $this->mask($telefone, '(##) ####-####') . '</a>';
        } else {
            $phone = 'Não informado';
        }

        return response()->json(['phone' => $phone]);
    }

    public function get_modelos($marca_id) {
        // Verifica se há modelos associados ao marca_id
        $modelos = Modelo::where('marca_id', $marca_id)->get();

        // Se não houver modelos, retorna uma resposta de erro
        if ($modelos->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Nenhum modelo encontrado.']);
        }

        $quantidadeModelos = $modelos->count();

        $modelosComQuantidade = $modelos->map(function ($modelo) {
            // Suponha que cada modelo tenha um método 'quantidade' ou você pode calcular com um relacionamento
            $quantidade = $modelo->quantidade; // Supondo que 'quantidade' seja um campo da tabela 'modelos'

            return [
                'id' => $modelo->id,
                'modelo_name' => $modelo->modelo_name,
                'quantidade' => $quantidade
            ];
        });

        // Caso contrário, retorna os modelos encontrados
        return response()->json([
                    'status' => 'success',
                    'data' => $modelosComQuantidade,
                    'quantidade_modelos' => $quantidadeModelos
        ]);
    }

    public function getModelos($brandId) {
        $modelos = Modelo::where('marca_id', $brandId)->get(); // Ajuste 'Modelo' e 'brand_id' conforme o nome das suas tabelas
        return response()->json($modelos);
    }

    public function getVersoes($modeloId) {
        $modelos = Versao::where('modeloId', $modeloId)->get(); // Ajuste 'Modelo' e 'brand_id' conforme o nome das suas tabelas
        return response()->json($modelos);
    }

    public function getCidades($ufId) {
        $modelos = App_Cidades::where('estado_id', $ufId)->get(); // Ajuste 'Modelo' e 'brand_id' conforme o nome das suas tabelas
        return response()->json($modelos);
    }

    public function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    public function limpaCPF_CNPJ($valor) {
        return preg_replace('/[^\d]/', '', trim($valor));
    }

    public function getEnderecoByCep($cep) {
        // Remover caracteres não numéricos do CEP (caso tenha algum formato inválido)
        $cep = preg_replace('/[^0-9]/', '', $cep);

        // Validar o CEP (8 dígitos numéricos)
        if (strlen($cep) != 8) {
            return false; // Retorna falso caso o CEP seja inválido
        }

        // URL para consulta do CEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        // Usando file_get_contents para obter os dados
        $json = file_get_contents($url);

        // Verifica se a resposta é válida
        if ($json === false) {
            return false; // Retorna falso caso o JSON não tenha sido obtido
        }

        // Decodifica o JSON
        $data = json_decode($json);

        // Verifica se a resposta do JSON tem a estrutura esperada
        if (isset($data->cep)) {
            return $data;
        }

        return false; // Caso não tenha o CEP ou dados inválidos
    }
}
