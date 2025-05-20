<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Models\Currency;
use App\Models\SocialMediaItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // Força HTTPS
        //if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
        //}

        Paginator::useBootstrap();

        $json_data = json_decode(file_get_contents(resource_path('lang/json_admin/menu_texts.json')));
        foreach ($json_data as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        $json_data1 = json_decode(file_get_contents(resource_path('lang/json_admin/admin_panel_texts.json')));
        foreach ($json_data1 as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        $json_data2 = json_decode(file_get_contents(resource_path('lang/json_admin/notification_texts.json')));
        foreach ($json_data2 as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        $json_data3 = json_decode(file_get_contents(resource_path('lang/json_admin/website_texts.json')));
        foreach ($json_data3 as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        View::composer('*', function ($view) {
            // Carrega os dados uma única vez para todas as views 
            $route = Route::currentRouteName();
            $g_setting = GeneralSetting::find(1);
            $currency_list = Currency::get();
            $sess_arr = Currency::where('is_default', 'Yes')->first();
            $social_media_items = SocialMediaItem::get();

            $item_row = $this->headTags();

            $view->with([
                'g_setting' => $g_setting,
                'currency_list' => $currency_list,
                'sess_arr' => $sess_arr,
                'social_media_items' => $social_media_items,
                'route' => $route,
                'item_row' => $item_row,
            ]);
        });
    }

    public function headTags() {
        $route = Route::currentRouteName();
        switch ($route) {
            case 'front_about':
                $item_row = \App\Models\PageAboutItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = 'Sobre Nós, o que fazemos, nosso objetivo, nossa meta, nossos valores!';
                break;
            case 'front_contact':
                $item_row = \App\Models\PageContactItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = 'Entre em contato conosco agora mesmo!';
                break;
            case 'front_blogs':
                $item_row = \App\Models\PageBlogItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : $item_row->post_content_short);
                break;
            case 'front_post':
                $main_url = \Illuminate\Support\Facades\Request::url();
                $slug = explode('blog/', $main_url);
                $item_row = \App\Models\Blog::where('post_slug', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : $item_row->post_content_short);
                break;
            case 'front_listing_result':
                $item_row = \App\Models\PageListingItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'Veículos');
                break;
            case 'busca_front_listing_result':
            case 'front_listing_result_veiculos':
                $item_row = \App\Models\PageListingItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'Veívulos');
                break;
            case 'front_listing_detail':
                $main_url = \Illuminate\Support\Facades\Request::url();
                $slug0 = explode('veiculo/', $main_url);
                $slug1 = explode('/', $slug0[1]);
                $id = $slug1[0];
                $slug = $slug1[1];
                $item_row = \App\Models\Listing::where('id', $id)->where('listing_slug', $slug)->first();
                $item_row->generic_seo_meta_description = !empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : clean($item_row->listing_description);
                break;
            case 'front_pricing':
                $item_row = \App\Models\PagePricingItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'Preços');
                break;
            case 'front_faq':
                $item_row = \App\Models\PageFaqItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'FAQ');
                break;
            case 'front_listing_location_all':
                $item_row = \App\Models\PageListingLocationItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'Todas as cidades');
                break;
            case 'front_listing_brand_all':
                $item_row = \App\Models\PageListingBrandItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : 'Todas as marcas');
                break;
            case 'front_dynamic_page':
                $main_url = \Illuminate\Support\Facades\Request::url();
                $slug = explode('page/', $main_url);
                $item_row = \App\Models\DynamicPage::where('dynamic_page_slug', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : clean($item_row->dynamic_page_content));
                break;
            case 'front_category':
                $main_url = \Illuminate\Support\Facades\Request::url();
                $slug = explode('categoria/', $main_url);
                $item_row = \App\Models\Category::where('category_slug', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : $item_row->category_name);
                break;
            case 'front_listing_agent_detail':
                $main_url = \Illuminate\Support\Facades\Request::url();
                $slug = explode('loja/user/', $main_url);
                $item_row = \App\Models\User::where('id', $slug[1])->orWhere('slug_user', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->address) ? $item_row->address : $item_row->name);
                break;
            case 'front_listing_location_detail':
                $main_url = Request::url();
                $slug = explode('veiculos/localizacao/', $main_url);
                $item_row = \App\Models\ListingLocation::where('listing_location_slug', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : ($item_row->listing_location_name));
                break;
            case 'front_listing_brand_detail':
                $main_url = Request::url();
                $slug = explode('veiculos/marcas/', $main_url);
                $item_row = \App\Models\ListingBrand::where('listing_brand_slug', $slug[1])->first();
                $item_row->generic_seo_meta_description = (!empty($item_row->seo_meta_description) ? $item_row->seo_meta_description : ($item_row->listing_brand_name));
                break;

            default:
                $item_row = \App\Models\PageHomeItem::where('id', 1)->first();
                $item_row->generic_seo_meta_description = 'Vila dos Carros - O seu shopping de automóveis';
                break;
        }

        return $item_row;
    }
}
