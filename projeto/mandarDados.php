<?php
// Conexão com o banco de dados MySQL usando PDO
require "./conexao.php";

// Consulta SQL para selecionar latitude e longitude
$sql = "SELECT latitude, longitude, ignicao, data, hora, numeroLinha FROM coordenada";

try {
    // Preparar e executar a consulta
    $resultado_sql = $pdo->prepare($sql);
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
