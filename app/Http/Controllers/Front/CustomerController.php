<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\LanguageMenuText;
use App\Models\LanguageWebsiteText;
use App\Models\LanguageNotificationText;
use App\Models\Transmission;
use App\Models\Combustivel;
use App\Models\Color;
use App\Models\Versao;
use App\Models\Modelo;
use App\Models\User;
use App\Models\Venda;
use App\Models\Banco;
use App\Models\Wishlist;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\ListingSocialItem;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\ListingVideo;
use App\Models\ListingAmenity;
use App\Models\Package;
use App\Models\App_Estados;
use App\Models\App_Cidades;
use App\Models\PackagePurchase;
use App\Models\Review;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\PageOtherItem;
use App\Mail\PurchaseCompletedEmailToCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Razorpay\Api\Api;
use Mollie\Laravel\Facades\Mollie;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomerController extends Controller {

    public function __construct() {
        $this->middleware('auth:web');
    }

    public function dashboard() {
        $page_other_item = PageOtherItem::where('id', 1)->first();

        $g_setting = GeneralSetting::where('id', 1)->first();
        $total_active_listing = Listing::where('listing_status', 'Active')
                ->where('user_id', Auth::user()->id)
                ->count();

        $total_pending_listing = Listing::where('listing_status', 'Pending')
                ->where('user_id', Auth::user()->id)
                ->count();

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', Auth::user()->id)
                ->where('currently_active', 1)
                ->first();
        
        $agent_detail = Auth::user();

        return view('front.customer_dashboard', compact('g_setting', 'total_active_listing', 'total_pending_listing', 'detail', 'page_other_item','agent_detail'));
    }

    public function venda_de_veiculos(Request $request) {

        $format = $request->export;

        $page_other_item = PageOtherItem::where('id', 1)->first();
        $query = Venda::with(['venda', 'user'])->where('user_id', Auth::user()->id);

        $user_id = Auth::user()->id;
        $bancos = Banco::get();
        $display_style = 'display: none;';

        $limite = 20;

        if ($request->has('como_nos_conheceu') && $request->como_nos_conheceu != '') {
            $query->where('como_nos_conheceu', 'like', '%' . $request->como_nos_conheceu . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('nomeCliente') && $request->nomeCliente != '') {
            $query->where('nomeCliente', 'like', '%' . $request->nomeCliente . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('cpfCliente') && $request->cpfCliente != '') {
            $query->where('cpfCliente', 'like', '%' . $request->cpfCliente . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('veiculo') && $request->veiculo != '') {
            $query->where('veiculo', 'like', '%' . $request->veiculo . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('placa') && $request->placa != '') {
            $query->where('placa', 'like', '%' . $request->placa . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('dataPagamentoFinanciamento') && $request->dataPagamentoFinanciamento != '') {
            $query->whereDate('dataPagamentoFinanciamento', $request->dataPagamentoFinanciamento);
            $display_style = 'display: block;';
        }
        if ($request->has('financeira') && $request->financeira != '') {
            $query->where('financeira', $request->financeira);
            $display_style = 'display: block;';
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
            $display_style = 'display: block;';
        }
        if ($request->has('limite') && $request->limite != '') {
            $limite = $request->limite;
            $display_style = 'display: block;';
        }

        if ($format === 'xls' || $format === 'xlsx') {
            $vendas = $query->take($limite)->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Nome Cliente')
                    ->setCellValue('B1', 'CPF Cliente')
                    ->setCellValue('C1', 'Veículo')
                    ->setCellValue('D1', 'Placa')
                    ->setCellValue('E1', 'Valor Total Financiamento')
                    ->setCellValue('F1', 'Previsão de Recebimento de Plus')
                    ->setCellValue('G1', 'Data Do Pagamento Do Financiamento')
                    ->setCellValue('H1', 'Financeira')
                    ->setCellValue('I1', 'Como Nos Conheceu?')
                    ->setCellValue('J1', 'Status');
            //#D9E1F2
            $headerStyle = $sheet->getStyle('A1:J1');
            $headerStyle->getFont()
                    ->setBold(true); // Negrito
            
            $headerStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2'); // Cor amarela
            
            $headerStyle->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER) // Alinhamento vertical ao centro
                    ->setWrapText(false);

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)
                        ->setAutoSize(true);
            }

            $row = 2;

            $i = 0;
            $total_liquido = 0;
            $total_liquido_geral = 0;
            $total_liquido_aprovado = 0;
            $total_liquido_pendente = 0;
            foreach ($vendas as $venda) {
                $porcentagem = $venda->venda->porcentagem / 100;
                $desconto = $venda->venda->desconto / 100;
                $total_liquido = ($venda->valorTotalFinanciamento * $porcentagem) * (1 - $desconto);

                if ($venda->status === 'aprovado') {
                    $total_liquido_aprovado += $total_liquido;
                } elseif ($venda->status === 'pendente') {
                    $total_liquido_pendente += $total_liquido;
                }

                $total_liquido_geral += $total_liquido;

                $sheet->setCellValue('A' . $row, $venda->nomeCliente);
                $sheet->setCellValue('B' . $row, $venda->cpfCliente);
                $sheet->setCellValue('C' . $row, $venda->veiculo);
                $sheet->setCellValue('D' . $row, $venda->placa);
                $sheet->setCellValue('E' . $row, number_format($venda->valorTotalFinanciamento, 2, ',', '.'));
                $sheet->setCellValue('F' . $row, number_format($total_liquido, 2, ',', '.'));
                $sheet->setCellValue('G' . $row, \Carbon\Carbon::parse($venda->dataPagamentoFinanciamento)->format('d/m/Y'));
                $sheet->setCellValue('H' . $row, $venda->venda->banco ?? 'N/A');
                $sheet->setCellValue('I' . $row, $venda->como_nos_conheceu);
                $sheet->setCellValue('J' . $row, $venda->status);
                $row++;
            }

            $fileName = 'vendas_veiculos.' . $format;
            $writer = ($format === 'xlsx') ? new Xlsx($spreadsheet) : new Xls($spreadsheet);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $vendas = $query->paginate($limite);
            return view('front.customer_venda_veiculos', compact('page_other_item', 'vendas', 'user_id', 'bancos', 'display_style'));
        }
    }

    public function update_profile() {
        $user_data = Auth::user();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $g_setting = GeneralSetting::where('id', 1)->first();
        return view('front.customer_update_profile', compact('user_data', 'g_setting', 'page_other_item'));
    }

    public function update_profile_confirm(Request $request) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user_data->id),
            ]
                ], [
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID,
            'email.unique' => ERR_EMAIL_EXIST
        ]);
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PROFILE_UPDATE);
    }

    public function update_password() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        return view('front.customer_update_password', compact('g_setting', 'page_other_item'));
    }

    public function update_password_confirm(Request $request) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'password' => 'required',
            're_password' => 'required|same:password',
                ], [
            'password.required' => ERR_PASSWORD_REQUIRED,
            're_password.required' => ERR_RE_PASSWORD_REQUIRED,
            're_password.same' => ERR_RE_PASSWORD_REQUIRED
        ]);
        $data['password'] = Hash::make($request->password);
        unset($data['re_password']);
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PASSWORD_UPDATE);
    }

    public function update_photo() {
        $user_data = Auth::user();
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        return view('front.customer_update_photo', compact('user_data', 'g_setting', 'page_other_item'));
    }

    public function update_photo_confirm(Request $request) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ], [
            'photo.required' => ERR_PHOTO_REQUIRED,
            'photo.image' => ERR_PHOTO_IMAGE,
            'photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'photo.max' => ERR_PHOTO_MAX
        ]);
        if ($user_data->photo != '') {
            unlink(public_path('uploads/user_photos/' . $user_data->photo));
        }
        $ext = $request->file('photo')->extension();
        $rand_value = md5(mt_rand(11111111, 99999999));
        $final_name = $rand_value . '.' . $ext;
        $request->file('photo')->move(public_path('uploads/user_photos/'), $final_name);
        $data['photo'] = $final_name;
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PHOTO_UPDATE);
    }

    public function update_banner() {
        $user_data = Auth::user();
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        return view('front.customer_update_banner', compact('user_data', 'g_setting', 'page_other_item'));
    }

    public function update_banner_confirm(Request $request) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ], [
            'banner.required' => ERR_PHOTO_REQUIRED,
            'banner.image' => ERR_PHOTO_IMAGE,
            'banner.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'banner.max' => ERR_PHOTO_MAX
        ]);
        if ($user_data->banner != '') {
            unlink(public_path('uploads/user_photos/' . $user_data->banner));
        }
        $ext = $request->file('banner')->extension();
        $rand_value = md5(mt_rand(11111111, 99999999));
        $final_name = $rand_value . '.' . $ext;
        $request->file('banner')->move(public_path('uploads/user_photos/'), $final_name);
        $data['banner'] = $final_name;
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_BANNER_UPDATE);
    }

    public function listing_view() {
        $user_data = Auth::user();
        $page_other_item = PageOtherItem::where('id', 1)->first();

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        if ($detail == null) {
            return redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if ($today > $expire_date) {
            return redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }


        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing = Listing::with('rListingBrand', 'rListingLocation')
                ->where('user_id', $user_data->id)
                ->get();
        return view('front.customer_listing_view', compact('g_setting', 'listing', 'page_other_item','user_data'));
    }

    public function listing_view_detail($id) {
        $user_data = Auth::user();
        $check_other = Listing::where('id', $id)->first();

        $page_other_item = PageOtherItem::where('id', 1)->first();

        if ((!$check_other) || ($check_other->user_id != $user_data->id)) {
            abort(404);
        }

        $g_setting = GeneralSetting::where('id', 1)->first();

        $listing = Listing::where('id', $id)->first();
        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
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

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        $total_amenities = $detail->rPackage->total_amenities;
        $total_photos = $detail->rPackage->total_photos;
        $total_videos = $detail->rPackage->total_videos;
        $total_social_items = $detail->rPackage->total_social_items;
        $total_additional_features = $detail->rPackage->total_additional_features;

        return view('front.customer_listing_view_detail', compact('g_setting', 'listing', 'listing_brand', 'listing_location', 'amenity', 'listing_photos', 'listing_videos', 'listing_additional_features', 'listing_social_items', 'listing_amenities', 'existing_amenities_array', 'total_amenities', 'total_photos', 'total_videos', 'total_social_items', 'total_additional_features', 'page_other_item'));
    }

    public function listing_add() {
        $user_data = Auth::user();

        $page_other_item = PageOtherItem::where('id', 1)->first();
        $estados = App_Estados::orderBy('estado_nome', 'asc')->get();

        // Check if he has access to add listing
        $listing_error_message = '';
        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        $total_listing_added_by_customer = Listing::where('user_id', $user_data->id)
                ->count();

        $total_amenities = 0;
        $total_photos = 0;
        $total_videos = 0;
        $total_social_items = 0;
        $total_additional_features = 0;
        $allow_featured = 0;

        if ($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        } else {
            // Date Over Check
            $today = date('Y-m-d');
            $expire_date = $detail->package_end_date;
            /* if ($today > $expire_date) {
              return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
              } */

            // Maximum Quota Check
            /* $remaining_listing = $detail->rPackage->total_listings - $total_listing_added_by_customer;
              if ($remaining_listing == 0) {
              return Redirect()->route('customer_package')->with('error', MAXIMUM_LIMIT_REACHED);
              } */

            $total_amenities = $detail->rPackage->total_amenities;
            $total_photos = $detail->rPackage->total_photos;
            $total_videos = $detail->rPackage->total_videos;
            $total_social_items = $detail->rPackage->total_social_items;
            $total_additional_features = $detail->rPackage->total_additional_features;
            $allow_featured = $detail->rPackage->allow_featured;
        }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing = Listing::get();
        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
        $listing_location = ListingLocation::orderBy('id', 'asc')->get();
        $amenity = Amenity::orderBy('id', 'asc')->get();
        $transmissions = Transmission::orderBy('id', 'asc')->get();
        $colors = Color::orderBy('id', 'asc')->get();
        $combustiveis = Combustivel::orderBy('id', 'asc')->get();
        return view('front.customer_listing_add', compact('g_setting', 'listing', 'listing_brand', 'listing_location', 'amenity', 'listing_error_message', 'total_amenities', 'total_photos', 'total_videos', 'total_social_items', 'total_additional_features', 'allow_featured', 'page_other_item', 'transmissions', 'colors', 'combustiveis', 'estados'));
    }

    public function listing_add_store(Request $request) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $request->validate([
            'listing_name' => 'required',
            'listing_description' => 'required',
            'listing_brand_id' => 'required',
            'listing_modelo_id' => 'required',
            'versao_id' => 'required',
            'listing_type' => 'required',
            'listing_tipo_veiculo' => 'required',
            'listing_email' => 'required',
            'listing_featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'listing_price' => 'required'
                ],
                [
                    'listing_name.required' => ERR_NAME_REQUIRED,
                    'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
                    'listing_brand_id.required' => 'A marca é obrigatória',
                    'listing_modelo_id.required' => 'O modelo é obrigatório',
                    'versao_id.required' => 'A versão é obrigatória',
                    'listing_type.required' => 'O estado do veículo é obrigatório',
                    'listing_tipo_veiculo.required' => 'O tipo de veículo é obrigatório',
                    'listing_email.required' => 'O e-mail é obrigatório',
                    'listing_featured_photo.required' => ERR_PHOTO_REQUIRED,
                    'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
                    'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                    'listing_featured_photo.max' => ERR_PHOTO_MAX,
                    'listing_price.required' => ERR_PRICE_REQUIRED
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'listings'");
        $ai_id = $statement[0]->Auto_increment;

        $rand_value = md5(mt_rand(11111111, 99999999));
        $ext = $request->file('listing_featured_photo')->extension();
        $final_name = $rand_value . '.' . $ext;
        $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos'), $final_name);

        $listing = new Listing();
        $data = $request->only($listing->getFillable());
        if (empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if (preg_match('/\s/', $data['listing_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }
        $data['listing_featured_photo'] = $final_name;
        $data['user_id'] = $user_data->id;
        $data['admin_id'] = 0;
        $data['listing_status'] = 'Pending';
        if ($request->is_featured == null) {
            $data['is_featured'] = 'No';
        } else {
            $data['is_featured'] = $request->is_featured;
        }
        $corId = $request->listing_exterior_color;
        $combustivelId = $request->listing_fuel_type;
        $cambioId = $request->listing_transmission;
        $versaoId = $request->versao_id;
        $canal = $request->canal;
        unset($data['listing_exterior_color']);
        unset($data['listing_fuel_type']);
        unset($data['listing_exterior_color']);
        unset($data['versao_id']);
        unset($data['listing_brand_id']);
        unset($data['listing_modelo_id']);
        if (!empty($cambioId)) {
            $cambio = Transmission::where('id', $cambioId)->first();
            if ($cambio) {
                $data['listing_transmission'] = $cambio->transmission_name;
                $data['listing_transmission_id'] = $cambio->id;
            }
        }
        if (!empty($combustivelId)) {
            $combustivel = Combustivel::where('id', $combustivelId)->first();
            if ($combustivel) {
                $data['listing_fuel_type'] = $combustivel->combustivel_name;
                $data['listing_fuel_type_id'] = $combustivel->id;
            }
        }
        if (!empty($corId)) {
            $color = Color::where('id', $corId)->first();
            if ($color) {
                $data['listing_exterior_color'] = $color->color_name;
                $data['listing_exterior_color_id'] = $color->id;
            }
        }

        if (!empty($request->listing_brand_id)) {
            $brand = ListingBrand::where('id', $request->listing_brand_id)->first();
            if ($brand) {
                $data['vehicleMake'] = $brand->listing_brand_name;
                $data['listing_brand_id'] = $brand->id;
            }
        }
        if (!empty($request->listing_modelo_id)) {
            $mod = Modelo::where('id', $request->listing_modelo_id)->first();
            if ($mod) {
                $data['vehicleModel'] = $mod->modelo_name;
                $data['listing_modelo_id'] = $mod->id;
            }
        }

        if (!empty($versaoId)) {
            $versao = Versao::where('versao_slug', $versaoId)->where('modeloId', $request->listing_modelo_id)->first();
            if ($versao) {
                $data['versao'] = $versao->versao_name;
                $data['versao_id'] = $versao->id;
            }
        }
        if (!empty($request->listing_uf)) {
            $luf = App_Estados::where('estado_id', $request->listing_uf)->first();
            if ($luf) {
                $data['listing_uf'] = $luf->estado_uf;
            }
        }

        if (!empty($request->listing_location_id)) {
            $lCity = App_Cidades::where('cidade_id', $request->listing_location_id)->first();
            if ($lCity) {
                $slugCity = Str::slug($lCity->cidade_nome);
                $lLocation = ListingLocation::where('listing_location_slug', $slugCity)->first();
                if (isset($lLocation) && !empty($lLocation->id)) {
                    $data['listing_location_id'] = $lLocation->id;
                } else {

                    $objs = ListingLocation::create([
                        'listing_location_name' => $lCity->cidade_nome,
                        'listing_location_slug' => $slugCity,
                        'listing_location_photo' => 'images/sem-localizacao.png',
                        'seo_title' => $lCity->cidade_nome,
                        'canal' => $request->canal,
                        'cep' => $request->cep,
                        'seo_meta_description' => $lCity->cidade_nome,
                    ]);

                    $data['listing_location_id'] = $objs->id;
                }
            }
        }

        $data['listing_price'] = $this->limpaCPF_CNPJ($request->listing_price);
        $data_placa = $this->limpaString($request->placa);
        $data['placa'] = strtoupper($this->mask($data_placa, '###-####'));
        $data_cep = $this->limpaCPF_CNPJ($request->cep);
        $data['cep'] = ($this->mask($data_cep, '#####-###'));

        $data['vehicleValue'] = $data['listing_price'];
        $data['vehicleManufactureYear'] = $data['anofabricacao'];
        $data['vehicleModelYear'] = $data['listing_model_year'];
        if ($request->listing_type == 'Novo') {
            $data['newVehicle'] = 'S';
        }
        if ($request->listing_type == 'Usado') {
            $data['newVehicle'] = 'N';
        }

        $listing->fill($data)->save();

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
            // No photo is selected
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
                    $obj->canal = $canal;
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
                    $obj->canal = $canal;
                    $obj->save();
                }
            }
        }
        return redirect()->route('customer_listing_view')->with('success', SUCCESS_LISTING_ADD);
    }

    public function listing_edit($id) {
        $user_data = Auth::user();
        $check_other = Listing::where('id', $id)->first();
        $estados = App_Estados::orderBy('estado_nome', 'asc')->get();

        $page_other_item = PageOtherItem::where('id', 1)->first();

        if ((!$check_other) || ($check_other->user_id != $user_data->id)) {
            abort(404);
        }

        $g_setting = GeneralSetting::where('id', 1)->first();

        $listing = Listing::where('id', $id)->first();
        $listing_brand = ListingBrand::orderBy('id', 'asc')->get();
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

        $transmissions = Transmission::orderBy('id', 'asc')->get();
        $colors = Color::orderBy('id', 'asc')->get();
        $combustiveis = Combustivel::orderBy('id', 'asc')->get();

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        $total_amenities = $detail->rPackage->total_amenities;
        $total_photos = $detail->rPackage->total_photos;
        $total_videos = $detail->rPackage->total_videos;
        $total_social_items = $detail->rPackage->total_social_items;
        $total_additional_features = $detail->rPackage->total_additional_features;
        $allow_featured = $detail->rPackage->allow_featured;
        $placa = $this->limpaString($listing->placa);

        return view('front.customer_listing_edit', compact('g_setting', 'listing', 'listing_brand', 'listing_location', 'amenity', 'listing_photos', 'listing_videos', 'listing_additional_features', 'listing_social_items', 'listing_amenities', 'existing_amenities_array', 'total_amenities', 'total_photos', 'total_videos', 'total_social_items', 'total_additional_features', 'allow_featured', 'page_other_item', 'transmissions', 'colors', 'combustiveis', 'placa', 'estados'));
    }

    public function listing_update(Request $request, $id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $listing = Listing::findOrFail($id);
        $data = $request->only($listing->getFillable());

        if ($request->hasFile('listing_featured_photo')) {

            $request->validate([
                'listing_featured_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ], [
                'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
                'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_featured_photo.max' => ERR_PHOTO_MAX
            ]);

            unlink(public_path('uploads/listing_featured_photos/' . $listing->listing_featured_photo));

            // Uploading the file
            $ext = $request->file('listing_featured_photo')->extension();
            $rand_value = md5(mt_rand(11111111, 99999999));
            $final_name = $rand_value . '.' . $ext;
            $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos/'), $final_name);

            unset($data['listing_featured_photo']);
            $data['listing_featured_photo'] = $final_name;
        }

        $request->validate([
            'listing_name' => [
                'required'
            ],
            'listing_slug' => [
                'required'
            ],
            'listing_description' => 'required',
            'listing_price' => 'required'
                ], [
            'listing_name.required' => ERR_NAME_REQUIRED,
            'listing_slug.required' => ERR_SLUG_REQUIRED,
            'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
            'listing_price.required' => ERR_PRICE_REQUIRED
        ]);
        if (empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if (preg_match('/\s/', $data['listing_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }

        $corId = $request->listing_exterior_color;
        $combustivelId = $request->listing_fuel_type;
        $cambioId = $request->listing_transmission;
        $versaoId = $request->versao_id;
        unset($data['listing_exterior_color']);
        unset($data['listing_fuel_type']);
        unset($data['listing_exterior_color']);
        unset($data['versao_id']);
        unset($data['listing_brand_id']);
        unset($data['listing_modelo_id']);
        if (!empty($cambioId)) {
            $cambio = Transmission::where('id', $cambioId)->first();
            if ($cambio) {
                $data['listing_transmission'] = $cambio->transmission_name;
                $data['listing_transmission_id'] = $cambio->id;
            }
        }
        if (!empty($combustivelId)) {
            $combustivel = Combustivel::where('id', $combustivelId)->first();
            if ($combustivel) {
                $data['listing_fuel_type'] = $combustivel->combustivel_name;
                $data['listing_fuel_type_id'] = $combustivel->id;
            }
        }
        if (!empty($corId)) {
            $color = Color::where('id', $corId)->first();
            if ($color) {
                $data['listing_exterior_color'] = $color->color_name;
                $data['listing_exterior_color_id'] = $color->id;
            }
        }

        if (!empty($request->listing_brand_id)) {
            $brand = ListingBrand::where('id', $request->listing_brand_id)->first();
            if ($brand) {
                $data['vehicleMake'] = $brand->listing_brand_name;
                $data['listing_brand_id'] = $brand->id;
            }
        }
        if (!empty($request->listing_modelo_id)) {
            $mod = Modelo::where('id', $request->listing_modelo_id)->first();
            if ($mod) {
                $data['vehicleModel'] = $mod->modelo_name;
                $data['listing_modelo_id'] = $mod->id;
            }
        }
        if (!empty($versaoId)) {
            $versao = Versao::where('versao_slug', $versaoId)->where('modeloId', $request->listing_modelo_id)->first();
            if ($versao) {
                $data['versao'] = $versao->versao_name;
                $data['versao_id'] = $versao->id;
            }
        }
        unset($data['listing_uf']);
        unset($data['listing_location_id']);
        if (!empty($request->listing_uf)) {
            $luf = App_Estados::where('estado_id', $request->listing_uf)->first();
            if ($luf) {
                $data['listing_uf'] = $luf->estado_uf;
            }
        }
        if (!empty($request->listing_location_id)) {
            $lCity = App_Cidades::where('cidade_id', $request->listing_location_id)->first();
            if ($lCity) {
                $slugCity = Str::slug($lCity->cidade_nome);
                $lLocation = ListingLocation::where('listing_location_slug', $slugCity)->first();
                if (isset($lLocation) && !empty($lLocation->id)) {
                    $data['listing_location_id'] = $lLocation->id;
                } else {

                    $objs = ListingLocation::create([
                        'listing_location_name' => $lCity->cidade_nome,
                        'listing_location_slug' => $slugCity,
                        'listing_location_photo' => 'images/sem-localizacao.png',
                        'seo_title' => $lCity->cidade_nome,
                        'canal' => $data['canal'],
                        'seo_meta_description' => $lCity->cidade_nome,
                    ]);

                    $data['listing_location_id'] = $objs->id;
                }
                //
            }
        }

        $data['listing_price'] = $this->limpaCPF_CNPJ($request->listing_price);
        $data_placa = $this->limpaString($request->placa);
        $data['placa'] = strtoupper($this->mask($data_placa, '###-####'));

        $data_cep = $this->limpaCPF_CNPJ($request->cep);
        $data['cep'] = strtoupper($this->mask($data_cep, '#####-###'));

        $listing->fill($data)->save();

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
            // No photo is selected
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
                    $obj->listing_id = $id;
                    $obj->photo = $final_photo_name;
                    $obj->save();
                }
            }
        }


        // Video
        if ($request->youtube_video_id != '') {
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
        if ($request->social_icon != '') {
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
        if ($request->additional_feature_name != '') {
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
        return redirect()->route('customer_listing_view')->with('success', SUCCESS_LISTING_EDIT);
    }

    public function listing_delete($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing = Listing::findOrFail($id);

        $currentFeaturedPath = public_path('uploads/listing_featured_photos/' . $listing->listing_featured_photo);
        if (file_exists($currentFeaturedPath)) {
            unlink($currentFeaturedPath);
        }

        //unlink(public_path('uploads/listing_featured_photos/' . $listing->listing_featured_photo));
        $listing->delete();

        ListingAmenity::where('listing_id', $id)->delete();
        ListingSocialItem::where('listing_id', $id)->delete();
        ListingVideo::where('listing_id', $id)->delete();
        ListingAdditionalFeature::where('listing_id', $id)->delete();

        $all_photos = ListingPhoto::where('listing_id', $id)->get();
        foreach ($all_photos as $item) {
            $currentPhotoPath = public_path('uploads/listing_photos/' . $item->photo);
            if (file_exists($currentPhotoPath)) {
                unlink($currentPhotoPath);
            }
            //unlink(public_path('uploads/listing_photos/' . $item->photo));
        }

        ListingPhoto::where('listing_id', $id)->delete();

        // Success Message and redirect
        return Redirect()->back()->with('success', SUCCESS_LISTING_DELETE);
    }

    public function listing_delete_social_item($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_social_item = ListingSocialItem::findOrFail($id);
        $listing_social_item->delete();
        return Redirect()->back()->with('success', SUCCESS_SOCIAL_ITEM_DELETE);
    }

    public function listing_delete_photo($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_photo = ListingPhoto::findOrFail($id);

        $currentPhotoPath = public_path('uploads/listing_photos/' . $listing_photo->photo);
        if (file_exists($currentPhotoPath)) {
            unlink($currentPhotoPath);
        }
        //unlink(public_path('uploads/listing_photos/' . $listing_photo->photo));

        $listing_photo->delete();
        return Redirect()->back()->with('success', SUCCESS_PHOTO_DELETE);
    }

    public function listing_delete_video($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_video = ListingVideo::findOrFail($id);
        $listing_video->delete();
        return Redirect()->back()->with('success', SUCCESS_VIDEO_DELETE);
    }

    public function listing_delete_additional_feature($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $listing_additional_feature = ListingAdditionalFeature::findOrFail($id);
        $listing_additional_feature->delete();
        return Redirect()->back()->with('success', SUCCESS_ADDITIONAL_FEATURE_DELETE);
    }

    public function submit_review(Request $request) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();
        $request->validate([
            'review' => 'required'
                ], [
            'review.required' => ERR_REVIEW_REQUIRED
        ]);

        // Logged in user. As this is front end, user must be a customer
        $review = new Review;
        $review->listing_id = $request->listing_id;
        $review->agent_id = $user_data->id;
        $review->agent_type = 'Customer';
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        return Redirect()->back()->with('success', SUCCESS_RATING_PLACED);
    }

    public function package() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $package = Package::orderBy('package_order', 'asc')->get();
        return view('front.customer_package', compact('g_setting', 'package', 'page_other_item'));
    }

     public function free_enroll_auto($id,$user_id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */


        // Make all other previous packages status to 0 and this package status 1
        $data['currently_active'] = 0;

        PackagePurchase::where('user_id', $user_id)->update($data);

        // Selected Package Detail
        $package_detail = Package::where('id', $id)->first();
        $valid_days = $package_detail->valid_days;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+$valid_days days"));

        $obj = new PackagePurchase;
        $obj->user_id = $user_id;
        $obj->package_id = $id;
        $obj->transaction_id = '';
        $obj->paid_amount = 0;
        $obj->payment_method = '';
        $obj->payment_status = 'Completed';
        $obj->package_start_date = $start_date;
        $obj->package_end_date = $end_date;
        $obj->currently_active = 1;
        $obj->save();
        //return redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_ENROLL);
    }
    public function free_enroll($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $user_data = Auth::user();

        // Make all other previous packages status to 0 and this package status 1
        $data['currently_active'] = 0;

        PackagePurchase::where('user_id', $user_data->id)->update($data);

        // Selected Package Detail
        $package_detail = Package::where('id', $id)->first();
        $valid_days = $package_detail->valid_days;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+$valid_days days"));

        $obj = new PackagePurchase;
        $obj->user_id = $user_data->id;
        $obj->package_id = $id;
        $obj->transaction_id = '';
        $obj->paid_amount = 0;
        $obj->payment_method = '';
        $obj->payment_status = 'Completed';
        $obj->package_start_date = $start_date;
        $obj->package_end_date = $end_date;
        $obj->currently_active = 1;
        $obj->save();
        return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_ENROLL);
    }

    public function my_reviews() {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $page_other_item = PageOtherItem::where('id', 1)->first();

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        if ($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if ($today > $expire_date) {
            return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }


        $reviews = Review::where('agent_id', $user_data->id)->where('agent_type', 'Customer')
                ->orderBy('id', 'asc')
                ->paginate(10);
        return view('front.customer_my_reviews', compact('g_setting', 'reviews', 'page_other_item'));
    }

    public function review_edit($id) {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $review_single = Review::findOrFail($id);
        return view('front.customer_my_review_edit', compact('review_single', 'page_other_item'));
    }

    public function review_update(Request $request, $id) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $review = Review::findOrFail($id);
        $data = $request->only($review->getFillable());
        $request->validate([
            'review' => 'required'
        ]);
        $review->fill($data)->save();
        return redirect()->route('customer_my_reviews')->with('success', SUCCESS_REVIEW_UPDATE);
    }

    public function review_delete($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $review = Review::findOrFail($id);
        $review->delete();
        return Redirect()->back()->with('success', SUCCESS_REVIEW_DELETE);
    }

    public function wishlist() {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $page_other_item = PageOtherItem::where('id', 1)->first();

        $detail = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->where('currently_active', 1)
                ->first();

        if ($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if ($today > $expire_date) {
            return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }


        $wishlist = Wishlist::where('user_id', $user_data->id)->orderBy('id', 'asc')->paginate(10);
        return view('front.customer_wishlist', compact('g_setting', 'wishlist', 'page_other_item'));
    }

    public function wishlist_delete($id) {
        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        $obj = Wishlist::findOrFail($id);
        $obj->delete();
        return Redirect()->back()->with('success', SUCCESS_ITEM_REMOVED_FROM_WISHLIST);
    }

    public function purchase_history() {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $package_purchase = PackagePurchase::with('rPackage')
                ->where('user_id', $user_data->id)
                ->orderBy('id', 'desc')
                ->get();
        return view('front.customer_package_purchase_history', compact('g_setting', 'package_purchase', 'page_other_item'));
    }

    public function purchase_history_detail($id) {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $detail = PackagePurchase::with('rPackage')
                ->where('id', $id)
                ->first();
        if (!$detail) {
            abort(404);
        }
        return view('front.customer_package_purchase_history_detail', compact('g_setting', 'detail', 'page_other_item'));
    }

    public function invoice($id) {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        $detail = PackagePurchase::with('rPackage')
                ->where('id', $id)
                ->first();

        if (!$detail) {
            abort(404);
        }
        return view('front.customer_package_purchase_invoice', compact('user_data', 'g_setting', 'detail', 'page_other_item'));
    }

    public function buy_package($id) {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $package_detail = Package::where('id', $id)->first();
        $page_other_item = PageOtherItem::where('id', 1)->first();
        session()->put('package_id', $id);
        session()->put('package_name', $package_detail->package_name);
        session()->put('package_price', $package_detail->package_price);
        return view('front.customer_package_buy', compact('g_setting', 'page_other_item'));
    }

    public function paypal() {
        if (!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $client = $g_setting->paypal_client_id;
        $secret = $g_setting->paypal_secret_key;

        $final_price = session()->get('package_price') * session()->get('currency_value');
        $final_price = round($final_price, 2);

        $admin_amount = session()->get('package_price');

        $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                        $client, // ClientID
                        $secret // ClientSecret
                )
        );

        $paymentId = request('paymentId');
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId(request('PayerID'));

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal($final_price);

        $amount->setCurrency(session()->get('currency_name'));
        $amount->setTotal($final_price);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        $result = $payment->execute($execution, $apiContext);

        if ($result->state == 'approved') {
            /* if(env('PROJECT_MODE') == 0) {
              return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
              } else { */

            $paid_amount = $result->transactions[0]->amount->total;

            // Make all other previous packages status to 0 and this package status 1
            $data['currently_active'] = 0;
            PackagePurchase::where('user_id', $user_data->id)->update($data);

            // Selected Package Detail
            $package_detail = Package::where('id', session()->get('package_id'))->first();
            $valid_days = $package_detail->valid_days;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+$valid_days days"));

            $obj = new PackagePurchase;
            $obj->user_id = $user_data->id;
            $obj->package_id = session()->get('package_id');
            $obj->transaction_id = $paymentId;
            $obj->paid_amount = $final_price;
            $obj->paid_currency = session()->get('currency_name');
            $obj->paid_currency_symbol = session()->get('currency_symbol');
            $obj->admin_amount = $admin_amount;
            $obj->payment_method = 'PayPal';
            $obj->payment_status = 'Completed';
            $obj->package_start_date = $start_date;
            $obj->package_end_date = $end_date;
            $obj->currently_active = 1;
            $obj->save();

            // Send Email To Customer
            $payment_method = 'PayPal';
            $et_data = EmailTemplate::where('id', 8)->first();
            $subject = $et_data->et_subject;
            $message = $et_data->et_content;

            $message = str_replace('[[customer_name]]', $user_data->name, $message);
            $message = str_replace('[[transaction_id]]', $paymentId, $message);
            $message = str_replace('[[payment_method]]', $payment_method, $message);
            $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $paid_amount, $message);
            $message = str_replace('[[payment_status]]', 'Completed', $message);
            $message = str_replace('[[package_start_date]]', $start_date, $message);
            $message = str_replace('[[package_end_date]]', $end_date, $message);
            Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

            session()->forget('package_id');
            session()->forget('package_name');
            session()->forget('package_price');

            return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            /* } */
        } else {
            return redirect()->to('/');
        }
    }

    public function stripe() {
        if (!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $stripe_secret_key = $g_setting->stripe_secret_key;

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount * session()->get('currency_value');
        $final_price = round($final_price, 2);

        \Stripe\Stripe::setApiKey($stripe_secret_key);

        if (isset($_POST['stripeToken'])) {
            \Stripe\Stripe::setVerifySslCerts(false);

            $token = $_POST['stripeToken'];
            $response = \Stripe\Charge::create([
                'amount' => $final_price * 100,
                'currency' => session()->get('currency_name'),
                'description' => 'Stripe Payment',
                'source' => $token,
                'metadata' => ['order_id' => uniqid()],
            ]);

            $bal = \Stripe\BalanceTransaction::retrieve($response->balance_transaction);
            $balJson = $bal->jsonSerialize();

            /* if(env('PROJECT_MODE') == 0) {
              return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
              } else { */
            $paid_amount = $balJson['amount'] / 100;

            // Make all other previous packages status to 0 and this package status 1
            $data['currently_active'] = 0;
            PackagePurchase::where('user_id', $user_data->id)->update($data);

            // Selected Package Detail
            $package_detail = Package::where('id', session()->get('package_id'))->first();
            $valid_days = $package_detail->valid_days;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+$valid_days days"));

            $obj = new PackagePurchase;
            $obj->user_id = $user_data->id;
            $obj->package_id = session()->get('package_id');
            $obj->transaction_id = $response->balance_transaction;
            $obj->paid_amount = $final_price;
            $obj->paid_currency = session()->get('currency_name');
            $obj->paid_currency_symbol = session()->get('currency_symbol');
            $obj->admin_amount = $admin_amount;
            $obj->payment_method = 'Stripe';
            $obj->payment_status = 'Completed';
            $obj->package_start_date = $start_date;
            $obj->package_end_date = $end_date;
            $obj->currently_active = 1;
            $obj->save();

            // Send Email To Customer
            $payment_method = 'Stripe';

            $et_data = EmailTemplate::where('id', 8)->first();
            $subject = $et_data->et_subject;
            $message = $et_data->et_content;

            $message = str_replace('[[customer_name]]', $user_data->name, $message);
            $message = str_replace('[[transaction_id]]', $response->balance_transaction, $message);
            $message = str_replace('[[payment_method]]', $payment_method, $message);
            $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $paid_amount, $message);
            $message = str_replace('[[payment_status]]', 'Completed', $message);
            $message = str_replace('[[package_start_date]]', $start_date, $message);
            $message = str_replace('[[package_end_date]]', $end_date, $message);
            Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

            session()->forget('package_id');
            session()->forget('package_name');
            session()->forget('package_price');

            return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            /* } */
        }
    }

    public function razorpay(Request $request) {
        if (!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount * session()->get('currency_value');
        $final_price = round($final_price, 2);

        $input = $request->all();
        $api = new Api($g_setting->razorpay_key_id, $g_setting->razorpay_key_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

                $payId = $response->id;

                /* if(env('PROJECT_MODE') == 0) {
                  return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
                  } else { */

                // Make all other previous packages status to 0 and this package status 1
                $data['currently_active'] = 0;
                PackagePurchase::where('user_id', $user_data->id)->update($data);

                // Selected Package Detail
                $package_detail = Package::where('id', session()->get('package_id'))->first();
                $valid_days = $package_detail->valid_days;
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime("+$valid_days days"));

                $obj = new PackagePurchase;
                $obj->user_id = $user_data->id;
                $obj->package_id = session()->get('package_id');
                $obj->transaction_id = $payId;
                $obj->paid_amount = $final_price;
                $obj->paid_currency = session()->get('currency_name');
                $obj->paid_currency_symbol = session()->get('currency_symbol');
                $obj->admin_amount = $admin_amount;
                $obj->payment_method = 'RazorPay';
                $obj->payment_status = 'Completed';
                $obj->package_start_date = $start_date;
                $obj->package_end_date = $end_date;
                $obj->currently_active = 1;
                $obj->save();

                // Send Email To Customer
                $payment_method = 'Razorpay';

                $et_data = EmailTemplate::where('id', 8)->first();
                $subject = $et_data->et_subject;
                $message = $et_data->et_content;

                $message = str_replace('[[customer_name]]', $user_data->name, $message);
                $message = str_replace('[[transaction_id]]', $payId, $message);
                $message = str_replace('[[payment_method]]', $payment_method, $message);
                $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $final_price, $message);
                $message = str_replace('[[payment_status]]', 'Completed', $message);
                $message = str_replace('[[package_start_date]]', $start_date, $message);
                $message = str_replace('[[package_end_date]]', $end_date, $message);
                Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

                session()->forget('package_id');
                session()->forget('package_name');
                session()->forget('package_price');

                return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
                /* } */
            } catch (Exception $e) {
                return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
            }
        }
    }

    public function flutterwave(Request $request) {
        if (!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount * session()->get('currency_value');
        $final_price = round($final_price, 2);

        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $g_setting->flutterwave_secret_key;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);

        if ($response->status == 'success') {
            /* if(env('PROJECT_MODE') == 0) {
              return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
              } else { */
            // Make all other previous packages status to 0 and this package status 1
            $data['currently_active'] = 0;
            PackagePurchase::where('user_id', $user_data->id)->update($data);

            // Selected Package Detail
            $package_detail = Package::where('id', session()->get('package_id'))->first();
            $valid_days = $package_detail->valid_days;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+$valid_days days"));

            $obj = new PackagePurchase;
            $obj->user_id = $user_data->id;
            $obj->package_id = session()->get('package_id');
            $obj->transaction_id = $tnx_id;
            $obj->paid_amount = $final_price;
            $obj->paid_currency = session()->get('currency_name');
            $obj->paid_currency_symbol = session()->get('currency_symbol');
            $obj->admin_amount = $admin_amount;
            $obj->payment_method = 'Flutterwave';
            $obj->payment_status = 'Completed';
            $obj->package_start_date = $start_date;
            $obj->package_end_date = $end_date;
            $obj->currently_active = 1;
            $obj->save();

            // Send Email To Customer
            $payment_method = 'Flutterwave';

            $et_data = EmailTemplate::where('id', 8)->first();
            $subject = $et_data->et_subject;
            $message = $et_data->et_content;

            $message = str_replace('[[customer_name]]', $user_data->name, $message);
            $message = str_replace('[[transaction_id]]', $tnx_id, $message);
            $message = str_replace('[[payment_method]]', $payment_method, $message);
            $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $final_price, $message);
            $message = str_replace('[[payment_status]]', 'Completed', $message);
            $message = str_replace('[[package_start_date]]', $start_date, $message);
            $message = str_replace('[[package_end_date]]', $end_date, $message);
            Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

            session()->forget('package_id');
            session()->forget('package_name');
            session()->forget('package_price');

            return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            /* } */
        } else {
            return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
        }
    }

    public function mollie(Request $request) {
        if (!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount * session()->get('currency_value');
        $final_price = round($final_price, 2);

        Mollie::api()->setApiKey($g_setting->mollie_api_key);

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => session()->get('currency_name'),
                'value' => '' . sprintf('%0.2f', $final_price) . '',
            ],
            'description' => env('APP_NAME'),
            'redirectUrl' => route('customer_payment_mollie_notify'),
        ]);
        $payment = Mollie::api()->payments()->get($payment->id);

        session()->put('payment_id', $payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function mollie_notify(Request $request) {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount * session()->get('currency_value');
        $final_price = round($final_price, 2);

        Mollie::api()->setApiKey($g_setting->mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()) {
            /* if(env('PROJECT_MODE') == 0) {
              return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
              } else { */

            // Make all other previous packages status to 0 and this package status 1
            $data['currently_active'] = 0;
            PackagePurchase::where('user_id', $user_data->id)->update($data);

            // Selected Package Detail
            $package_detail = Package::where('id', session()->get('package_id'))->first();
            $valid_days = $package_detail->valid_days;
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+$valid_days days"));

            $obj = new PackagePurchase;
            $obj->user_id = $user_data->id;
            $obj->package_id = session()->get('package_id');
            $obj->transaction_id = $payment->id;
            $obj->paid_amount = $final_price;
            $obj->paid_currency = session()->get('currency_name');
            $obj->paid_currency_symbol = session()->get('currency_symbol');
            $obj->admin_amount = $admin_amount;
            $obj->payment_method = 'Mollie';
            $obj->payment_status = 'Completed';
            $obj->package_start_date = $start_date;
            $obj->package_end_date = $end_date;
            $obj->currently_active = 1;
            $obj->save();

            // Send Email To Customer
            $payment_method = 'Mollie';

            $et_data = EmailTemplate::where('id', 8)->first();
            $subject = $et_data->et_subject;
            $message = $et_data->et_content;

            $message = str_replace('[[customer_name]]', $user_data->name, $message);
            $message = str_replace('[[transaction_id]]', $payment->id, $message);
            $message = str_replace('[[payment_method]]', $payment_method, $message);
            $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $final_price, $message);
            $message = str_replace('[[payment_status]]', 'Completed', $message);
            $message = str_replace('[[package_start_date]]', $start_date, $message);
            $message = str_replace('[[package_end_date]]', $end_date, $message);
            Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

            session()->forget('package_id');
            session()->forget('package_name');
            session()->forget('package_price');

            return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            /* } */
        } else {
            return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
        }
    }

    private function limpaCPF_CNPJ($value) {
// Remove caracteres especiais de CPF/CNPJ
        return preg_replace('/[^0-9]/', '', $value);
    }

    private function limpaString($value) {
        return preg_replace('/[^0-9a-z]/i', '', $value);
    }

    private function mask($val, $mask) {
// Aplica uma máscara ao CPF/CNPJ
        $masked = '';
        $k = 0;
        for ($i = 0;
                $i < strlen($mask);
                $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $masked .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $masked .= $mask[$i];
                }
            }
        }
        return $masked;
    }

    public function listing_add_versao(Request $request) {
        $request->validate([
            'versao_name' => 'required'
                ],
                [
                    'versao_name.required' => ERR_NAME_REQUIRED
        ]);

        $versao = new Versao();
        $data = $request->only($versao->getFillable());
        if (empty($data['versao_slug'])) {
            unset($data['versao_slug']);
            $data['versao_slug'] = Str::slug($request->versao_name);
        }
        if (preg_match('/\s/', $data['versao_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }

        $resVersao = Versao::where('versao_slug', $data['versao_slug'])->where('modeloId', $data['modeloId'])->first();
        if (isset($resVersao) && !empty($resVersao->id)) {
            $result = array(
                'status' => 'error',
                'msg' => 'Esta versão já existe para esse modelo!'
            );
            //return Redirect()->back()->with('error', "Versão já existe!");
        } else {
            $versao->fill($data)->save();
            $lastInsertedId = $versao->id;

            $result = array(
                'id' => $lastInsertedId,
                'slug' => $data['versao_slug'],
                'name' => $request->versao_name,
                'modeloId' => $data['modeloId'],
                'status' => 'success',
                'msg' => 'Versão adicionada com sucesso!'
            );

            //return Redirect()->back()->with('success', "Versão adicionada com sucesso!");
        }
        return response()->json($result);
        /* $result= array(
          'id'=>$lastInsertedId,
          'status'=>'success'
          );
          return response()->json($result); */
    }
}
