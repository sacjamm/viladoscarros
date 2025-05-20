vehicle_models<?php

header("Content-Type: application/json; charset=utf-8");

// Função para realizar requisições à API Credere
function CredereApiRequest($endpoint, $token, $method = "GET", $data = null) {
    $url = "https://app.meucredere.com.br/api/v1" . $endpoint;

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json",
        "Accept: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        throw new Exception("Erro cURL: $err");
    }

    if ($httpCode >= 400) {
        throw new Exception("Erro HTTP: $httpCode - Resposta: $response");
    }

    return json_decode($response, true);
}

function PT_limpaCPF_CNPJ(?string $valor): string {
    if (is_null($valor)) {
        return '';
    }
    return preg_replace('/[.\-_,\/()\s]/', '', $valor);
}

// Função para listar lojas pelo CNPJ
function listarLojas($cnpj, $token) {
    $endpoint = "/stores?per_page=100&cnpj=$cnpj";
    return CredereApiRequest($endpoint, $token, "GET");
}

// Função para listar cores disponíveis
function listarCores($token) {
    $endpoint = "/domains?types=vehicle_color";
    return CredereApiRequest($endpoint, $token, "GET");
}
function listarModelos($token,$termo='') {
    $endpoint = "/vehicle_models/search?q={$termo}";
    return CredereApiRequest($endpoint, $token, "GET"); 
}  
 
// Função para criar estoque
function criarEstoque($data, $token) {
    $endpoint = "/estoques";
    return CredereApiRequest($endpoint, $token, "POST", $data);
}
 
$servername = "localhost"; // Altere se necessário
$username = "viladoscarroscom"; // Substitua pelo usuário do banco
$password = "2wwWZeFShg"; // Substitua pela senha do banco
$database = "viladoscarroscom";
// Configuração inicial
$token = "755651ff38b184148e5b338b6f40e07607c58cd73f81abe5849ad5fd9aef0b1c"; // Substitua pelo token seguro

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}
try {
    // Buscar CNPJs no banco de dados
   /* $query = "SELECT id, cnpj_credere FROM users WHERE cnpj_credere IS NOT NULL AND cnpj_credere != ''"; // Ajuste conforme necessário
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $cnpj = PT_limpaCPF_CNPJ($row['cnpj_credere']);
        $user_id = intval($row['id']);
        $lojas = listarLojas($cnpj, $token);
         if(isset($lojas['stores'])){
          foreach ($lojas['stores'] as $stores) {
          $store_id = $stores['id'];

          // Atualiza o campo loja_credere do usuário
          $store_id = $conn->real_escape_string($store_id); // Segurança contra SQL Injection
          $update = "UPDATE users SET loja_credere = '$store_id' WHERE id = $user_id";
          $conn->query($update);

          echo "<p>Usuário ID {$user_id} atualizado com loja_credere = {$store_id}</p>";
          }
          } 
    }*/

    /*$cores = listarCores($token);

    echo "Cores disponíveis:\n";

    foreach ($cores['domains']['vehicle_color'] as $cor) {
        echo '<pre>';
        var_dump($cor);
        echo '</pre>';
        $cor_label = $cor['identifier'];
        $cor_id = $cor['id'];
        $cor_label = $conn->real_escape_string($cor_label); // Segurança contra SQL Injection
        $cor_id = $conn->real_escape_string($cor_id); // Segurança contra SQL Injection
        $update = "UPDATE colors SET color_credere = '$cor_id' WHERE color_slug = '$cor_label'";
        $conn->query($update);
    }*/
    
    $query = "SELECT id, vehicleModel FROM listings  
    ";
     /*$query = "SELECT id, listing_brand_name FROM listing_brands"; // Ajuste conforme necessário*/
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        
        //$modelos = listarModelos($token,$row['listing_brand_name']); 
        /*$array = [
            'store_id'=>$row['loja_credere'],
            'name'=>$row['listing_name'],
            'manufacture_year'=>$row['anofabricacao'],
            'model_year'=>$row['listing_model_year'],
            'vehicle_model_id'=>$modelos['vehicle_model']['vehicle_brand']['id'],
            'price_cents'=>intval($row['listing_price'].'00'),
            'km_mileage'=>$row['listing_mileage'],
            'color_id'=>$row['color_credere'],
        ];*/
        
            echo '<pre>';
        var_dump($row);
        echo '</pre>'; 
    
        
    }

} catch (Exception $e) {
    //echo "Erro: " . $e->getMessage();
} finally {
    $conn->close();
}
