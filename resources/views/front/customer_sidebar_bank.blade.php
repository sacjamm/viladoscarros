<a type="button" 
   onclick="toggleMenu()" 
   id="toggleMenuButton" 
   class="btn btn-block btn-dark btn-sm mb-2 d-md-none">
    <i class="fas fa-eye"></i> Abrir Menu Do Cliente
</a>

<ul id="menu_sidebar_customer" class="d-none d-md-block">
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