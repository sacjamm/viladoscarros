<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

require_once($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../mysqli/MysqliDb.php');

$servername = "localhost"; // Altere se necessário
$username = "viladoscarroscom"; // Substitua pelo usuário do banco
$password = "2wwWZeFShg"; // Substitua pela senha do banco
$database = "viladoscarroscom";
// Configuração inicial
$token = "755651ff38b184148e5b338b6f40e07607c58cd73f81abe5849ad5fd9aef0b1c"; // Substitua pelo token seguro

$mysqli = new mysqli($servername, $username, $password, $database);
$db = new MysqliDb($mysqli);

// Função para realizar requisições à API Credere
function CredereApiRequest($endpoint, $token, $method = "GET", $data = null) {
    $url = "https://app.meucredere.com.br/api/v1" . $endpoint;

    $client = new Client();

    $options = [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ];

    if ($data && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
        $options['json'] = $data;
    }

    try {
        $response = $client->request(strtoupper($method), $url, $options);
        return json_decode($response->getBody(), true);
    } catch (RequestException $e) {
        $errorBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'Nenhuma resposta';
        return json_decode($errorBody, true);
        //throw new Exception("Erro na requisição HTTP: " . $e->getMessage() . " - Resposta: " . $errorBody);
    }
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

function listarVehicleModels($token, $method = "GET", $row = []) {
    $endpoint = '/vehicle_models/search?q=' . $row['model'] . '&model_year=' . $row['vehicleModelYear'];
    return CredereApiRequest($endpoint, $token, $method);
}

function listarEstoque($token, $per_page = 100, $page = 1, $sort = 'created_at_desc', $status = 'true') {
    $endpoint = "/vehicles?per_page=$per_page&page=$page&sort=$sort&active=$status";
    return CredereApiRequest($endpoint, $token, "GET");
}

// Função para criar estoque
function criarEstoque($data, $token) {
    $endpoint = "/vehicles";
    return CredereApiRequest($endpoint, $token, "POST", $data);
}

if (isset($_GET['action']) && $_GET['action'] == 'actions_credere') {
    $statusColor = false;
// Buscar CNPJs no banco de dados
    $query = "SELECT id, cnpj_credere FROM users WHERE cnpj_credere IS NOT NULL AND cnpj_credere != ''"; // Ajuste conforme necessário
    $result = $db->rawQuery($query);

    foreach ($result as $row) {
        $row = (array) $row;
        $cnpj = PT_limpaCPF_CNPJ($row['cnpj_credere']);
        $user_id = intval($row['id']);
        $lojas = listarLojas($cnpj, $token);
        if (isset($lojas['stores'])) {
            foreach ($lojas['stores'] as $stores) {
                $store_id = $stores['id'];

                // Atualiza o campo loja_credere do usuário
                $store_id = $db->escape($store_id); // Segurança contra SQL Injection
                //$update = "UPDATE users SET loja_credere = '$store_id' WHERE id = $user_id";
                $db->where('id', $user_id)->update('users', ['loja_credere' => $store_id]);
                $statusColor = true;
                //Aecho "<p>Usuário ID {$user_id} atualizado com loja_credere = {$store_id}</p>";
            }
        }
    }
    if ($statusColor) {
        $cores = listarCores($token);
        foreach ($cores['domains']['vehicle_color'] as $cor) {

            $cor_label = $db->escape($cor['identifier']); // Segurança contra SQL Injection
            $cor_id = $db->escape($cor['id']); // Segurança contra SQL Injection

            $db->where('color_slug', $cor_label)->update('colors', ['color_credere' => $cor_id]);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'add_estoque_credere') {
    $query = "SELECT u.loja_credere, e.listing_name,e.anofabricacao, "
            . "e.listing_model_year, e.listing_price, e.listing_mileage, e.id AS idEstoque, "
            . "e.vehicleModel, e.vehicleModelYear, c.color_credere,e.credere_model_id "
            . "FROM listings e "
            . "LEFT JOIN users u ON u.id = e.user_id "
            . "LEFT JOIN colors c ON c.id = e.listing_exterior_color_id "
            . "WHERE e.credere_model_id = 0 ";
    $result = $db->rawQuery($query);

    foreach ($result as $row) {
        $row = (array) $row;
        $arr_name = array('model' => (trim($row['listing_name'])), 'vehicleModelYear' => $row['vehicleModelYear']);

        try {
            $modelos = listarVehicleModels($token, 'GET', $arr_name);
        } catch (RequestException $e) {
            try {
                echo "Erro na requisição com listing_name: " . $row['listing_name'] . "\n";
                $arr_model = array('model' => urlencode(trim($row['vehicleModel'])), 'vehicleModelYear' => $row['vehicleModelYear']);
                $modelos = listarVehicleModels($token, 'GET', $arr_model);
            } catch (RequestException $e2) {
                echo "Erro também com vehicleModel: " . $row['vehicleModel'] . "\n";
                continue; // pula para o próximo veículo
            }
        }

        if (isset($modelos->vehicle_model->id)) {
            $model_id = $modelos->vehicle_model->id;

            $array = [
                'store_id' => $row['loja_credere'],
                'name' => $row['listing_name'],
                'manufacture_year' => $row['anofabricacao'],
                'model_year' => $row['vehicleModelYear'],
                'vehicle_model_id' => $model_id,
                'price_cents' => intval($row['listing_price'] . '00'),
                'km_mileage' => $row['listing_mileage'],
                'color_id' => $row['color_credere'],
            ];
            $create = [];
            if ($row['estoqueCredere_id'] <= 0) {
                $create = criarEstoque($array, $token);
            }
            $tableData = ['credere_model_id' => $model_id];
            if (isset($create['vehicle']['object_type'])) {
                $estoqueCredere_id = $create['vehicle']['id'];
                $tableData['estoqueCredere_id'] = $estoqueCredere_id;
            }
            $updt2 = $db->where('id', $row['idEstoque'])->update('listings', $tableData);
        } else {
            echo "<p style='color:red;font-weight:600;'>Modelo não encontrado para '{$row['listing_name']}' / Ano {$row['vehicleModelYear']}<br>"
            . "Message: {$modelos['error']['message']} <br>"
            . "Status: {$modelos['error']['status']}</p>";
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'list_estoque_credere') {
    $per_page = 100;
    $page = 1;
    $sort = 'created_at_desc';
    $status = 'true';

    while (true) {
        $list = listarEstoque($token, $per_page, $page, $sort, $status);

        // Se não houver veículos ou a lista estiver vazia, encerra o loop
        if (empty($list['vehicles'])) {
            break;
        }

        foreach ($list['vehicles'] as $vehicles) {
            $db->join("users u", "e.user_id=u.id", "LEFT");
            $db->join("colors c", "e.listing_exterior_color_id=c.id", "LEFT");
            $db->where('u.loja_credere', $vehicles['store']['id']);
            $db->where('c.color_credere', $vehicles['color']['id']);
            $db->where('e.vehicleModelYear', $vehicles['model_year']);
            $db->where('e.anofabricacao', $vehicles['manufacture_year']);
            $db->where('e.listing_mileage', $vehicles['km_mileage']);
            $db->where('e.listing_price', rtrim($vehicles['price_cents'], '00'));
            $db->where('e.credere_model_id', $vehicles['vehicle_model']['id']);
            $db->where("e.credere_model_id", 0, ">");
            $v = $db->getOne('listings e', 'e.id AS idEstoque');
            if ($v) {
                $v = (array) $v;
                $estoqueCredere_id = $vehicles['id'] ?? 0;
                $tableData = ['estoqueCredere_id' => $estoqueCredere_id];
                $updt2 = $db->where('id', $v['idEstoque'])->update('listings', $tableData);
            }
        }

        if (count($list['vehicles']) < $per_page) {
            break;
        }

        $page++; // Avança para a próxima página

        echo "Página $page processada com " . count($list['vehicles']) . " registros.<br>";
    }

    echo "Sincronização finalizada com sucesso.";
}

    