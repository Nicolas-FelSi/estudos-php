<?php
// Conexão com o banco de dados MySQL usando PDO
require "./conexao.php";
session_start();
ob_start();

$_SESSION['mapa_importado'] = true;

// $idPlanilha = $_SESSION['id_planilha'];
$idPlanilhaGet = $_GET['id_planilha'];

// Consulta SQL para selecionar latitude e longitude
$sql = "SELECT latitude, longitude, ignicao, data, hora, numero_linha FROM coordenada WHERE fk_id_planilha = :id_planilha";

try {
    // Preparar e executar a consulta
    $resultado_sql = $pdo->prepare($sql);
    $resultado_sql->bindParam(':id_planilha', $idPlanilhaGet);
    $resultado_sql->execute();
    
    // Obter os resultados como uma matriz associativa
    $resultados = $resultado_sql->fetchAll(PDO::FETCH_ASSOC); 
    
    // Retornar os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo "Erro ao obter os dados do banco de dados: " . $e->getMessage();
}

// Fechar a conexão com o banco de dados
$pdo = null;
?>
