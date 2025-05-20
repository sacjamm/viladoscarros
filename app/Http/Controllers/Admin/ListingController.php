<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Modelo;
use App\Models\Versao;
use App\Models\ListingModelo;
use App\Models\ListingSocialItem;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\ListingVideo;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\ListingAmenity;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class ListingController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function indexOriginal() {
        $listing = Listing::with('rListingBrand', 'rListingLocation')->get();
        return view('admin.listing_view', compact('listing'));
    }

    public function index(Request $request) {

        $listings = Listing::with(['rListingBrand', 'rListingLocation', 'user'])
                ->select('listings.*')
                ->get();

        if ($request->ajax()) {
            return DataTables::of($listings)
                            ->addColumn('action', function ($row) {
                                return '<a href="' . route('front_listing_detail', ['id' => $row->id, 'slug' => $row->listing_slug]) . '" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> Ver anúncio</a>
                                        <a href="#" onClick="return abreModal(' . $row->id . ');return false;" class="btn btn-primary btn-sm" data-target="#detail_info' . $row->id . '">Detalhes</a>                            
                    <a href="' . route('admin_listing_delete', $row->id) . '" class="btn btn-danger btn-sm" onClick="return confirm(\'Are you sure?\');"><i class="fas fa-trash-alt"></i></a>
                            <a href="' . route('admin_listing_edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
                            })
                            ->editColumn('listing_featured_photo', function ($row) {
                                if ($row->canal === 'dsautoestoque') {
                                    if ($row->listing_featured_photo == 'images/sem-veiculo.jpg') {
                                        return '<img src="' . asset($row->listing_featured_photo) . '" class="w_200">';
                                    } else {
                                        if ($row->listing_image_alterada_admin == 1) {
                                            return '<img src="' . asset('uploads/listing_featured_photos/' . $row->listing_featured_photo) . '" alt="" class="w_200">';
                                        } else {
                                            return '<img src="' . $row->listing_featured_photo . '" class="w_200">';
                                        }
                                    }
                                } else {
                                    return '<img src="' . asset('uploads/listing_featured_photos/' . $row->listing_featured_photo) . '" class="w_200">';
                                }
                            })
                            ->editColumn('listing_status', function ($row) {
                                return $row->listing_status == 'Active' ? '<a href="" onclick="listingStatus(' . $row->id . ')"><input type="checkbox" checked data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>' : '<a href="" onclick="listingStatus(' . $row->id . ')"><input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>';
                            })
                            ->editColumn('name', function ($row) {
                                return ' <a href="'.route('admin_customer_detail',$row->user->id).'" class="text-primary" target="_blank"><i class="fa fa-external-link"></i> '.$row->user->name.'</a>';
                            })
                            ->rawColumns(['listing_featured_photo', 'action', 'name', 'listing_status'])
                            ->make(true);
        }

        return view('admin.listing_view');
    }

    public function detalhe($id) {
        $listing = Listing::with(['rListingBrand', 'rListingLocation'])->findOrFail($id);
        $listing_phone = $this->limpaCPF_CNPJ($listing->listing_phone);
        if (strlen($listing_phone) >= 11) {
            $listing_phone = $this->mask($listing_phone, '(##) #####-####');
        } else
        if (strlen($listing_phone) <= 10) {
            $listing_phone = $this->mask($listing_phone, '(##) ####-####');
        } else {
            $listing_phone = 'S/N';
        }

        if ($listing->canal == 'dsautoestoque') {
            if ($listing->listing_featured_photo == 'images/sem-veiculo.jpg') {
                $img = '<img src="' . asset('images/sem-veiculo.jpg') . '" alt="" class="w_200">';
            } else {
                if ($listing->listing_image_alterada_admin == 1) {
                    $img = '<img src="' . asset('uploads/listing_featured_photos/' . $listing->listing_featured_photo) . '" alt="" class="w_200">';
                } else {
                    $img = '<img src="' . $listing->listing_featured_photo . '" alt="" class="w_200">';
                }
            }
        } else {
            $img = '<img src="' . asset('uploads/listing_featured_photos/' . $listing->listing_featured_photo) . '" alt="" class="w_200">';
        }

        $rota = route('admin_listing_edit', $listing->id);
        return response()->json([
                    'listing_name' => $listing->listing_name,
                    'listing_slug' => $listing->listing_slug,
                    'listing_address' => $listing->listing_address,
                    'listing_email' => $listing->listing_email,
                    'listing_map' => $listing->listing_map,
                    'listing_website' => '<a href="' . $listing->listing_website . '" target="_blank">' . $listing->listing_website . '</a>',
                    'listing_description' => $listing->listing_description,
                    'listing_price' => $listing->listing_price,
                    'listing_type' => $listing->listing_type,
                    'listing_exterior_color' => $listing->listing_exterior_color,
                    'listing_interior_color' => $listing->listing_interior_color,
                    'listing_cylinder' => $listing->listing_cylinder,
                    'listing_fuel_type' => $listing->listing_fuel_type,
                    'listing_transmission' => $listing->listing_transmission,
                    'listing_engine_capacity' => $listing->listing_engine_capacity,
                    'listing_vin' => $listing->listing_vin,
                    'listing_body' => $listing->listing_body,
                    'listing_seat' => $listing->listing_seat,
                    'listing_wheel' => $listing->listing_wheel,
                    'listing_door' => $listing->listing_door,
                    'listing_mileage' => $listing->listing_mileage,
                    'listing_model_year' => $listing->listing_model_year,
                    'listing_oh_monday' => $listing->listing_oh_monday,
                    'listing_oh_tuesday' => $listing->listing_oh_tuesday,
                    'listing_oh_wednesday' => $listing->listing_oh_wednesday,
                    'listing_oh_thursday' => $listing->listing_oh_thursday,
                    'listing_oh_friday' => $listing->listing_oh_friday,
                    'listing_oh_saturday' => $listing->listing_oh_saturday,
                    'listing_oh_sunday' => $listing->listing_oh_sunday,
                    'listing_featured_photo' => $img,
                    'listing_brand_name' => $listing->rListingBrand->listing_brand_name,
                    'listing_location_name' => $listing->rListingLocation->listing_location_name,
                    'listing_price' => number_format($listing->listing_price, 0, '', '.'),
                    'listing_phone' => $listing_phone,
                    'rota' => $rota,
        ]);
    }

    public function create() {
        $listing = Listing::get();
        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
        $listing_location = ListingLocation::orderBy('id', 'asc')->get();
        $amenity = Amenity::orderBy('id', 'asc')->get();
        return view('admin.listing_create', compact('listing', 'listing_brand', 'listing_location', 'amenity'));
    }

    public function store(Request $request) {

        $user_data = Auth::user();

        $request->validate([
            'listing_name' => 'required',
            'listing_slug' => 'required',
            'listing_description' => 'required',
            'listing_featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'listing_price' => 'required|numeric'
                ], [
            'listing_name.required' => ERR_NAME_REQUIRED,
            'listing_slug.required' => ERR_SLUG_REQUIRED,
            'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
            'listing_featured_photo.required' => ERR_PHOTO_REQUIRED,
            'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
            'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'listing_featured_photo.max' => ERR_PHOTO_MAX,
            'listing_price.required' => ERR_PRICE_REQUIRED,
            'listing_price.numeric' => ERR_PRICE_NUMERIC
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'listings'");
        $ai_id = $statement[0]->Auto_increment;

        $rand_value = md5(mt_rand(11111111, 99999999));
        $ext = $request->file('listing_featured_photo')->extension();
        $final_name = $rand_value . '.' . $ext;
        $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos'), $final_name);

        $obj = new Listing();
        $data = $request->only($obj->getFillable());
        if (empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if (preg_match('/\s/', $data['listing_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }
        $data['listing_featured_photo'] = $final_name;
        if (!empty($final_name)) {
            $data['listing_image_alterada_admin'] = 1;
            $data['canal'] = 'website';
        }
        $data['user_id'] = 0;
        $data['admin_id'] = $user_data->id;
        $obj->fill($data)->save();

        // Amenity
        if ($request->amenity != '') {
            $arr_amenity = array();
            foreach ($request->amenity as $item) {
                $arr_amenity[] = $item;
            }
            for ($i = 0; $i < count($arr_amenity); $i++) {
                $obj = new ListingAmenity;
                $obj->listing_id = $ai_id;
                $obj->amenity_id = $arr_amenity[$i];
                $obj->save();
            }
        }
        // Photo
        if ($request->photo_list == '') {
            //echo 'No photo selected';
        } else {
            foreach ($request->photo_list as $item) {
                $file_in_mb = $item->getSize() / 1024 / 1024;
                $main_file_ext = $item->extension();
                $main_mime_type = $item->getMimeType();

                if (($main_mime_type == 'image/jpeg' || $main_mime_type == 'image/png' || $main_mime_type == 'image/gif') && $file_in_mb <= 2) {
                    $rand_value = md5(mt_rand(11111111, 99999999));
                    $final_photo_name = $rand_value . '.' . $main_file_ext;
                    $item->move(public_path('uploads/listing_photos'), $final_photo_name);

                    $obj = new ListingPhoto;
                    $obj->listing_id = $ai_id;
                    $obj->photo = $final_photo_name;
                    $obj->canal = 'website';
                    $obj->save();
                }
            }
        }
        // Video
        if ($request->youtube_video_id[0] != '') {
            $arr_youtube_video_id = array();
            foreach ($request->youtube_video_id as $item) {
                $arr_youtube_video_id[] = $item;
            }
            for ($i = 0; $i < count($arr_youtube_video_id); $i++) {
                if ($arr_youtube_video_id[$i] != '') {
                    $obj = new ListingVideo;
                    $obj->listing_id = $ai_id;
                    $obj->youtube_video_id = $arr_youtube_video_id[$i];
                    $obj->save();
                }
            }
        }
        // Social Icons
        if ($request->social_icon[0] != '') {
            $arr_social_icon = array();
            $arr_social_url = array();
            foreach ($request->social_icon as $item) {
                $arr_social_icon[] = $item;
            }
            foreach ($request->social_url as $item) {
                $arr_social_url[] = $item;
            }
            for ($i = 0; $i < count($arr_social_icon); $i++) {
                if (($arr_social_icon[$i] != '') && ($arr_social_url[$i] != '')) {
                    $obj = new ListingSocialItem;
                    $obj->listing_id = $ai_id;
                    $obj->social_icon = $arr_social_icon[$i];
                    $obj->social_url = $arr_social_url[$i];
                    $obj->save();
                }
            }
        }
        // Additional Features
        if ($request->additional_feature_name[0] != '') {
            $arr_additional_feature_name = array();
            $arr_additional_feature_value = array();
            foreach ($request->additional_feature_name as $item) {
                $arr_additional_feature_name[] = $item;
            }
            foreach ($request->additional_feature_value as $item) {
                $arr_additional_feature_value[] = $item;
            }
            for ($i = 0; $i < count($arr_additional_feature_name); $i++) {
                if (($arr_additional_feature_name[$i] != '') && ($arr_additional_feature_value[$i] != '')) {
                    $obj = new ListingAdditionalFeature;
                    $obj->listing_id = $ai_id;
                    $obj->additional_feature_name = $arr_additional_feature_name[$i];
                    $obj->additional_feature_value = $arr_additional_feature_value[$i];
                    $obj->save();
                }
            }
        }
        return redirect()->route('admin_listing_view')->with('success', SUCCESS_ACTION);
    }

    public function edit($id) {

        $apiKey_OCR_Space = 'K86963407488957';

        $user_data = Auth::user();

        $listing = Listing::where('id', $id)->first();

        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
        $listing_modelo = Modelo::orderBy('id', 'asc')->get();
        $listing_location = ListingLocation::orderBy('id', 'asc')->get();
        $amenity = Amenity::orderBy('id', 'asc')->get();

        $existing_amenities_array = array();
        $listing_amenities = ListingAmenity::where('listing_id', $id)->orderBy('id', 'asc')->get();
        foreach ($listing_amenities as $row) {
            $existing_amenities_array[] = $row->amenity_id;
        }

        $listing_photos = ListingPhoto::where('listing_id', $id)->orderBy('id', 'asc')->get();
        $listing_videos = ListingVideo::where('listing_id', $id)->orderBy('id', 'asc')->get();
        $listing_additional_features = ListingAdditionalFeature::where('listing_id', $id)->orderBy('id', 'asc')->get();

        $listing_social_items = ListingSocialItem::where('listing_id', $id)->orderBy('id', 'asc')->get();

        return view('admin.listing_edit', compact('listing', 'listing_brand', 'listing_location', 'amenity', 'listing_photos', 'listing_videos', 'listing_additional_features', 'listing_social_items', 'listing_amenities', 'existing_amenities_array', 'listing_modelo'));
    }

    public function update(Request $request, $id) {

        $obj = Listing::findOrFail($id);
        $data = $request->only($obj->getFillable());
        if ($request->hasFile('listing_featured_photo')) {

            $request->validate([
                'listing_featured_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ], [
                'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
                'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_featured_photo.max' => ERR_PHOTO_MAX
            ]);

            $currentFeaturedPath = public_path('uploads/listing_featured_photos/' . $request->current_photo);
            if (file_exists($currentFeaturedPath)) {
                unlink($currentFeaturedPath);
            }
            // Uploading the file
            $ext = $request->file('listing_featured_photo')->extension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $final_name = $rand_value . '.' . $ext;
            $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos/'), $final_name);

            unset($data['listing_featured_photo']);
            $data['listing_featured_photo'] = $final_name;
            $data['listing_image_alterada_admin'] = 1;
        }

        $request->validate([
            'listing_name' => [
                'required'
            ],
            'listing_slug' => [
                'required'
            ],
            'listing_description' => 'required',
            'listing_price' => 'required|numeric'
                ], [
            'listing_name.required' => ERR_NAME_REQUIRED,
            'listing_slug.required' => ERR_SLUG_REQUIRED,
            'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
            'listing_price.required' => ERR_PRICE_REQUIRED,
            'listing_price.numeric' => ERR_PRICE_NUMERIC
        ]);
        if (empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if (preg_match('/\s/', $data['listing_slug'])) {
            return redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }
        $obj->fill($data)->save();

        // Amenity
        $existing_amenities_array = array();
        $arr_amenity = array();
        $result1 = array();
        $result2 = array();

        $listing_amenities = ListingAmenity::where('listing_id', $id)->orderBy('id', 'asc')->get();
        foreach ($listing_amenities as $row) {
            $existing_amenities_array[] = $row->amenity_id;
        }

        if ($request->amenity != '') {
            foreach ($request->amenity as $item) {
                $arr_amenity[] = $item;
            }
        }

        $result1 = array_values(array_diff($existing_amenities_array, $arr_amenity));
        if (!empty($result1)) {
            for ($i = 0; $i < count($result1); $i++) {
                ListingAmenity::where('listing_id', $id)
                        ->where('amenity_id', $result1[$i])
                        ->delete();
            }
        }

        $result2 = array_values(array_diff($arr_amenity, $existing_amenities_array));
        if (!empty($result2)) {
            for ($i = 0; $i < count($result2); $i++) {
                $obj = new ListingAmenity;
                $obj->listing_id = $id;
                $obj->amenity_id = $result2[$i];
                $obj->save();
            }
        }
        // Photo
        if ($request->photo_list == '') {
            //echo 'No photo selected';
        } else {
            foreach ($request->photo_list as $item) {
                $file_in_mb = $item->getSize() / 1024 / 1024;
                $main_file_ext = $item->extension();
                $main_mime_type = $item->getMimeType();

                if (($main_mime_type == 'image/jpeg' || $main_mime_type == 'image/png' || $main_mime_type == 'image/gif') && $file_in_mb <= 2) {
                    $rand_value = md5(mt_rand(11111111, 99999999));
                    $final_photo_name = $rand_value . '.' . $main_file_ext;
                    $item->move(public_path('uploads/listing_photos'), $final_photo_name);

                    if (!empty($final_photo_name)) {
                        $listing_image_alterada_admin = 1;
                    } else {
                        $listing_image_alterada_admin = 0;
                    }
                    $obj = new ListingPhoto;
                    $obj->listing_id = $id;
                    $obj->photo = $final_photo_name;
                    $obj->listing_image_alterada_admin = $listing_image_alterada_admin;
                    $obj->save();
                }
            }
        }
        // Video
        if ($request->youtube_video_id[0] != '') {
            $arr_youtube_video_id = array();
            foreach ($request->youtube_video_id as $item) {
                $arr_youtube_video_id[] = $item;
            }
            for ($i = 0; $i < count($arr_youtube_video_id); $i++) {
                if ($arr_youtube_video_id[$i] != '') {
                    $obj = new ListingVideo;
                    $obj->listing_id = $id;
                    $obj->youtube_video_id = $arr_youtube_video_id[$i];
                    $obj->save();
                }
            }
        }
        // Social Icons
        if ($request->social_icon[0] != '') {
            $arr_social_icon = array();
            $arr_social_url = array();
            foreach ($request->social_icon as $item) {
                $arr_social_icon[] = $item;
            }
            foreach ($request->social_url as $item) {
                $arr_social_url[] = $item;
            }
            for ($i = 0; $i < count($arr_social_icon); $i++) {
                if (($arr_social_icon[$i] != '') && ($arr_social_url[$i] != '')) {
                    $obj = new ListingSocialItem;
                    $obj->listing_id = $id;
                    $obj->social_icon = $arr_social_icon[$i];
                    $obj->social_url = $arr_social_url[$i];
                    $obj->save();
                }
            }
        }
        // Additional Features
        if ($request->additional_feature_name[0] != '') {
            $arr_additional_feature_name = array();
            $arr_additional_feature_value = array();
            foreach ($request->additional_feature_name as $item) {
                $arr_additional_feature_name[] = $item;
            }
            foreach ($request->additional_feature_value as $item) {
                $arr_additional_feature_value[] = $item;
            }
            for ($i = 0; $i < count($arr_additional_feature_name); $i++) {
                if (($arr_additional_feature_name[$i] != '') && ($arr_additional_feature_value[$i] != '')) {
                    $obj = new ListingAdditionalFeature;
                    $obj->listing_id = $id;
                    $obj->additional_feature_name = $arr_additional_feature_name[$i];
                    $obj->additional_feature_value = $arr_additional_feature_value[$i];
                    $obj->save();
                }
            }
        }
        return redirect()->route('admin_listing_view')->with('success', SUCCESS_ACTION);
    }

    public function destroy($id) {
        $listing = Listing::findOrFail($id);
        $currentFeaturedPath = public_path('uploads/listing_featured_photos/' . $listing->listing_featured_photo);
        if (file_exists($currentFeaturedPath)) {
            unlink($currentFeaturedPath);
        }
        $listing->delete();

        ListingAmenity::where('listing_id', $id)->delete();
        ListingSocialItem::where('listing_id', $id)->delete();
        ListingVideo::where('listing_id', $id)->delete();
        ListingAdditionalFeature::where('listing_id', $id)->delete();

        $all_photos = ListingPhoto::where('listing_id', $id)->get();
        foreach ($all_photos as $item) {
            $currentItemPath = public_path('uploads/listing_photos/' . $item->photo);
            if (file_exists($currentItemPath)) {
                unlink($currentItemPath);
            }
        }

        ListingPhoto::where('listing_id', $id)->delete();

        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function delete_social_item($id) {
        $listing_social_item = ListingSocialItem::findOrFail($id);
        $listing_social_item->delete();
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function delete_photo($id) {
        $listing_photo = ListingPhoto::findOrFail($id);
        $currentItemPath = public_path('uploads/listing_photos/' . $listing_photo->photo);
        if (file_exists($currentItemPath)) {
            unlink($currentItemPath);
        }
        $listing_photo->delete();
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function delete_video($id) {
        $listing_video = ListingVideo::findOrFail($id);
        $listing_video->delete();
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function delete_additional_feature($id) {
        $listing_additional_feature = ListingAdditionalFeature::findOrFail($id);
        $listing_additional_feature->delete();
        return redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function change_status($id) {
        $listing = Listing::find($id);
        if ($listing->listing_status == 'Active') {
            $listing->listing_status = 'Pending';
            $message = SUCCESS_ACTION;
            $listing->save();
        } else {
            $listing->listing_status = 'Active';
            $message = SUCCESS_ACTION;
            $listing->save();
        }
        return response()->json($message);
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

    function limpaCPF_CNPJ($valor) {
        return preg_replace('/[^\d]/', '', trim($valor));
    }

    public function getModelos($brandId) {

        $modelos = Modelo::where('marca_id', $brandId)->get(); // Ajuste 'Modelo' e 'brand_id' conforme o nome das suas tabelas
        return response()->json($modelos);
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

    public function getOcrTextFromImage($imageUrl, $apiKey) {
        // URL da API OCR.Space
        $url = 'https://api.ocr.space/parse/image';

        // Dados para enviar para a API OCR.Space
        $data = [
            'url' => $imageUrl,
            'apikey' => $apiKey,
            'language' => 'por', // Idioma Português (pode ser alterado conforme necessário)
        ];

        // Iniciar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Obter a resposta da API
        $response = curl_exec($ch);
        curl_close($ch);

        // Converter a resposta JSON em array
        $jsonResponse = json_decode($response, true);

        // Verificar se a resposta contém resultados
        if (isset($jsonResponse['ParsedResults'][0]['ParsedText'])) {
            return $jsonResponse['ParsedResults'][0]['ParsedText'];
        } else {
            return '';  // Caso não haja texto extraído
        }
    }
}
