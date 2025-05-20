<?php
function alterarEstruturaUsuarios($conn) {
    $sql = "ALTER TABLE users 
            MODIFY COLUMN email VARCHAR(255) NULL, 
            MODIFY COLUMN password VARCHAR(255) NULL";

    if ($conn->query($sql) === TRUE) {
        echo "Tabela users alterada com sucesso!";
    } else {
        echo "Erro ao alterar a tabela: " . $conn->error;
    }
}
function adicionarCampoTipoUser($conn) {
    /*$sql = "ALTER TABLE users 
            ADD COLUMN tipo_user VARCHAR(50) NOT NULL DEFAULT 'lojista'";*/
    $sql = "ALTER TABLE users 
            ADD COLUMN cnpj_credere VARCHAR(50) NULL DEFAULT NULL";

    if ($conn->query($sql) === TRUE) {
        echo "Campo tipo_user adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar o campo: " . $conn->error;
    }
}

// Conectar ao banco de dados
$servername = "localhost"; // Altere se necessário
$username = "viladoscarroscom_site"; // Substitua pelo usuário do banco
$password = "2wwWZeFShg"; // Substitua pela senha do banco
$database = "viladoscarroscom_site"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $database);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Chama a função para alterar a estrutura
//alterarEstruturaUsuarios($conn);
//adicionarCampoTipoUser($conn);

// Fecha a conexão
$conn->close();