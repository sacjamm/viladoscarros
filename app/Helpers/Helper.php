<?php

namespace App\Helpers;

class Helper {

    public static function formatarCPF($cpf) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
    }

    public static function mask($val, $mask) {
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

    public static function PT_limpaCPF_CNPJ($valor) {
        // Remove espaços em branco e caracteres indesejados usando uma expressão regular
        return preg_replace('/[^\d]/', '', trim($valor));
    }

    public static function limpa_string($valor) {
        $valor = trim($valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        $valor = str_replace("%", "", $valor);
        $valor = str_replace("*", "", $valor);
        $valor = str_replace("&", "", $valor);
        $valor = str_replace("¨", "", $valor);
        $valor = str_replace("$", "", $valor);
        $valor = str_replace("#", "", $valor);
        $valor = str_replace("@", "", $valor);
        $valor = str_replace("!", "", $valor);
        $valor = str_replace(" ", "", $valor);
        $valor = str_replace(" ", "", $valor);
        return $valor;
    }

    public static function montaTituloVeiculo($veiculo, $ID = 0, $canal = 'autoconf') {
        $marca = verificaString($veiculo['marca']);
        $modelo = verificaString($veiculo['modelo']);
        $versao = verificaString($veiculo['versao']);
        $anomodelo = verificaString($veiculo['anomodelo']);
        if ($canal === 'autoconf') {
            return trim(preg_replace('/\s+/', ' ', "$marca $modelo $versao $anomodelo [$canal]"));
        } else {
            return trim(preg_replace('/\s+/', ' ', "$marca $modelo $versao $anomodelo [$canal]"));
        }
    }

    public static function verificaString($valor) {
        return is_string($valor) ? trim($valor) : '';
    }
}
