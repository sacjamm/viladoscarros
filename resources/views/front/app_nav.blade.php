@php
$g_settings = \App\Models\GeneralSetting::where('id',1)->first();
$dynamic_pages = \App\Models\DynamicPage::get();
$page_about_item = \App\Models\PageAboutItem::where('id',1)->first();
$page_faq_item = \App\Models\PageFaqItem::where('id',1)->first();
$page_blog_item = \App\Models\PageBlogItem::where('id',1)->first();
$page_listing_item = \App\Models\PageListingItem::where('id',1)->first();
$page_pricing_item = \App\Models\PagePricingItem::where('id',1)->first();
$page_contact_item = \App\Models\PageContactItem::where('id',1)->first();
$page_listing_location_item = \App\Models\PageListingLocationItem::where('id',1)->first();
$page_listing_brand_item = \App\Models\PageListingBrandItem::where('id',1)->first();
@endphp

<!-- Start Navbar Area -->
<div id="stickymenu" class="navbar-area">

	<!-- Menu For Mobile Device -->
	<div class="mobile-nav">
		<a href="{{ url('/') }}" class="logo" 
                             title="Vila dos Carros - O seu shopping de automóveis" alt="Vila dos Carros - O seu shopping de automóveis">			                        
<picture>
    <source srcset="{{ asset('uploads/site_photos/webp/' . pathinfo($g_settings->logo, PATHINFO_FILENAME) . '.webp') }}" type="image/webp">
    <img src="{{ asset('uploads/site_photos/webp/' . pathinfo($g_settings->logo, PATHINFO_FILENAME) . '.webp') }}" 
         alt="Logo Vila dos Carros" 
         title="Vila dos Carros - O seu shopping de automóveis" 
         height="20">
</picture>
                        
                    
		</a>
	</div>
       
	<!-- Menu For Desktop Device -->
	<div class="main-nav">
		<div class="container container-nav">
		
			<nav class="navbar navbar-expand-md">
				<a class="navbar-brand" href="{{ url('/') }}" 
                             title="Vila dos Carros - O seu shopping de automóveis" alt="Vila dos Carros - O seu shopping de automóveis">                                        
    <picture>
    <source srcset="{{ asset('uploads/site_photos/webp/' . pathinfo($g_settings->logo, PATHINFO_FILENAME) . '.webp') }}" type="image/webp">
    <img src="{{ asset('uploads/site_photos/webp/' . pathinfo($g_settings->logo, PATHINFO_FILENAME) . '.webp') }}" 
         alt="Logo Vila dos Carros" 
         title="Vila dos Carros - O seu shopping de automóveis" 
         height="25">
</picture>
				</a>
				<div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
					<ul class="navbar-nav {{ $g_settings->layout_direction == 'ltr' ? 'ml-auto' : 'mr-auto' }}">

						<li class="nav-item">
							<a href="{{ url('/') }}" class="nav-link" 
                             title="{{ MENU_HOME }} - Vila dos Carros - O seu shopping de automóveis">{{ MENU_HOME }}</a>
						</li>

                        @if($page_listing_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_listing_result_veiculos') }}" class="nav-link" 
                             title="{{ MENU_LISTING }}">{{ MENU_LISTING }}</a>
						</li>
                        @endif
<li class="nav-item">
							<a href="{{ route('front_quero_vender') }}" class="nav-link" 
                             title="{{ MENU_SELL }}">{{ MENU_SELL }}</a>
						</li>
                        @if($page_pricing_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_pricing') }}" class="nav-link" 
                             title="{{ MENU_PRICING }}">{{ MENU_PRICING }}</a>
						</li>
                        @endif

						

                        @if($page_listing_location_item->status == 'Show' || $page_listing_brand_item->status == 'Show' || (!$dynamic_pages->isEmpty()))
						<li class="nav-item">
							<a href="#" class="nav-link dropdown-toggle" 
                             title="{{ MENU_PAGES }}">{{ MENU_PAGES }}</a>
							<ul class="dropdown-menu">
                                                            @if($page_about_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_about') }}" class="nav-link" 
                             title="{{ MENU_ABOUT }}">{{ MENU_ABOUT }}</a>
						</li>
						@endif

                                @if($page_listing_location_item->status == 'Show')
								<li class="nav-item">
									<a href="{{ route('front_listing_location_all') }}" class="nav-link" 
                             title="{{ MENU_LOCATION }}">{{ MENU_LOCATION }}</a>
								</li>
                                @endif

                                
                                @if($page_listing_brand_item->status == 'Show')
								<li class="nav-item">
									<a href="{{ route('front_listing_brand_all') }}" class="nav-link" 
                             title="{{ MENU_BRAND }}">Marcas</a>
								</li>
                                @endif
                                
                                @if($page_faq_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_faq') }}" class="nav-link" 
                             title="{{ MENU_FAQ }}">{{ MENU_FAQ }}</a>
						</li>
						@endif
                                

                                @if(!$dynamic_pages->isEmpty())
								@foreach($dynamic_pages as $row)
                                    <li class="nav-item">
                                        <a href="{{ url('page/'.$row->dynamic_page_slug) }}" class="nav-link" 
                             title="{{ $row->dynamic_page_name }}">{{ $row->dynamic_page_name }}</a>
                                    </li>
                                @endforeach
                                @endif
                                
                                
							</ul>
						</li>
                        @endif

						@if($page_blog_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_blogs') }}" class="nav-link" 
                             title="{{ MENU_BLOG }}">{{ MENU_BLOG }}</a>
						</li>
						@endif
<li class="nav-item">
                                        <a href="{{ route('front_lojas') }}" class="nav-link" 
                             title="Lojas">Lojas</a>
                                    </li>
                        @if($page_contact_item->status == 'Show')
						<li class="nav-item">
							<a href="{{ route('front_contact') }}" class="nav-link" 
                             title="{{ MENU_CONTACT }}">{{ MENU_CONTACT }}</a>
						</li>
                        @endif

					</ul>
				</div>
			</nav>
		</div>
	</div>
</div>
<!-- End Navbar Area -->
