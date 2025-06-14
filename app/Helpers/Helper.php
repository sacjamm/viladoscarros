<?php

namespace App\Helpers;

class Helper {

    public static function resizeAndConvertToWebp($sourcePath, $destPath, $width = 255, $height = 170, $quality = 80) {
        if (!file_exists($sourcePath))
            return false;

        $mime = mime_content_type($sourcePath);

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $src = @imagecreatefrompng($sourcePath);
                if (!$src)
                    return false;
                $image = imagecreatetruecolor(imagesx($src), imagesy($src));
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $white);
                imagecopy($image, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
                imagedestroy($src);
                break;
            case 'image/gif':
                $src = @imagecreatefromgif($sourcePath);
                if (!$src)
                    return false;
                $image = imagecreatetruecolor(imagesx($src), imagesy($src));
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $white);
                imagecopy($image, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
                imagedestroy($src);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        // Criar imagem redimensionada
        $resized = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

        if (!file_exists(dirname($destPath))) {
            mkdir(dirname($destPath), 0755, true);
        }

        imagewebp($resized, $destPath, $quality);

        imagedestroy($image);
        imagedestroy($resized);

        return $destPath;
    }

    public static function convertUrlImageToWebp($url, $outputPath, $quality = 80) {
        // Pega os headers para verificar se é uma imagem JPG

        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        $headers = @get_headers($url, 1, $context);
        if (!isset($headers['Content-Type']) || stripos($headers['Content-Type'], 'image/jpeg') === false) {
            return false;
        }


        // Baixa a imagem da URL
        $imageData = file_get_contents($url);
        if ($imageData === false) {
            return false;
        }

        // Cria a imagem a partir da string
        $image = imagecreatefromstring($imageData);
        if (!$image) {
            return false;
        }

        // Garante que o diretório de saída exista
        $dir = dirname($outputPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Salva como WebP
        $result = imagewebp($image, $outputPath, $quality);
        imagedestroy($image);

        return $result ? $outputPath : false;
    }

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
