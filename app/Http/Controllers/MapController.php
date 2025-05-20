<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller {

    public function index($endereco = null) {

        $address = $endereco; //"Endereço de Exemplo, Cidade, Estado, País"; // Insira o endereço desejado aqui
        return view('map', compact('address'));
    }
    public function render_map($endereco = null) {
        return $endereco;
    }
}
