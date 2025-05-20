<div class="text-center" style="text-align: center;">
    <img src="{{ asset('images/search-not-found.png') }}" alt="{{ asset('images/search-not-found.png') }}" title="Nenhum resultado encontrado"/>

    <p class="text-center" style="text-align: center;font-size:30px;margin-top:10%;">Sua busca retornou ({{ $total_registros }}) registros</p>
</div>
<script>
$(document).ready(function(){
    $('#quantidade_para_exibir').hide();
});
</script>
