<a type="button" 
   onclick="toggleMenu()" 
   id="toggleMenuButton" 
   class="btn btn-block btn-dark btn-sm mb-2 d-md-none">
    <i class="fas fa-eye"></i> Abrir Menu Do Cliente
</a>

<ul id="menu_sidebar_customer" class="d-none d-md-block">
	<li><a href="{{ route('customer_dashboard') }}" class="btn btn-md btn-block btn-dark">{{ DASHBOARD }}</a></li>
	<!--<li><a href="{{ route('customer_package') }}" class="btn btn-md btn-block btn-dark">{{ PACKAGES }}</a></li>-->
	<!--<li><a href="{{ route('customer_package_purchase_history') }}" class="btn btn-md btn-block btn-dark">{{ PURCHASE_HISTORY }}</a></li>-->
	<li><a href="{{ route('customer_listing_view') }}" class="btn btn-md btn-block btn-dark">{{ ALL_LISTINGS }}</a></li>
        
	<!--<li><a href="{{ route('customer_listing_add') }}" class="btn btn-md btn-block btn-dark">{{ ADD_LISTING }}</a></li>-->
    <!--<li><a href="{{ route('customer_my_reviews') }}" class="btn btn-md btn-block btn-dark">{{ MY_REVIEWS }}</a></li>-->
    <!--<li><a href="{{ route('customer_wishlist') }}" class="btn btn-md btn-block btn-dark">{{ WISHLIST }}</a></li>-->
	<!--<li><a href="{{ route('customer_update_profile') }}" class="btn btn-md btn-block btn-dark">{{ EDIT_PROFILE }}</a></li>-->
	<!--<li><a href="{{ route('customer_update_password') }}" class="btn btn-md btn-block btn-dark">{{ EDIT_PASSWORD }}</a></li>-->
	<!--<li><a href="{{ route('customer_update_photo') }}" class="btn btn-md btn-block btn-dark">{{ EDIT_PHOTO }}</a></li>-->
	<!--<li><a href="{{ route('customer_update_banner') }}" class="btn btn-md btn-block btn-dark">{{ EDIT_BANNER }}</a></li>-->
	<li><a href="{{ route('customer_vendadeveiculos') }}" class="btn btn-md btn-block btn-dark">Venda de veículos</a></li>
	<li><a href="{{ route('customer_logout') }}" class="btn btn-md btn-block btn-dark">{{ LOGOUT }}</a></li>
</ul>

<script>
    function toggleMenu() {
        // Seleciona o botão e o menu
        var button = document.getElementById('toggleMenuButton');
        var menu = document.getElementById('menu_sidebar_customer');
        
        if (menu.classList.contains('d-none')) {
            menu.classList.remove('d-none');
            button.innerHTML = '<i class="fas fa-remove"></i> Fechar Menu Do Cliente';
        } else {
            menu.classList.add('d-none');
            button.innerHTML = '<i class="fas fa-eye"></i> Abrir Menu Do Cliente';
        }

        //button.innerHTML = '<i class="fas fa-eye"></i> Abrir Menu Do Cliente';
        // Altera o texto do botão
        /*if ($(menu).is(':visible') == false) {
            button.innerHTML = '<i class="fas fa-remove"></i> Fechar Menu Do Cliente';
            $(menu).hide('fast');
            $(menu).addClass('d-none');
            $(menu).addClass('d-md-block');
        } 
        if ($(menu).is(':visible') == true) {
            button.innerHTML = '<i class="fas fa-eye"></i> Abrir Menu Do Cliente';
            $(menu).removeClass('d-none');
            $(menu).removeClass('d-md-block');
            $(menu).show('fast');
        }*/
        
        // Alterna a visibilidade do menu
        //$(menu).slideToggle('fast');
    }
</script>