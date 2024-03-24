<?php
// Conexão com o banco de dados MySQL usando PDO
$host = 'localhost';
$dbname = 'projeto';
$username = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    exit();
}

// Consulta SQL para selecionar latitude e longitude
$sql = "SELECT latitude, longitude, ignicao, data, hora, numero_linha FROM coordenadas";

try {
    // Preparar e executar a consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Obter os resultados como uma matriz associativa
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
    // Retornar os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo "Erro ao obter os dados do banco de dados: " . $e->getMessage();
}

// Fechar a conexão com o banco de dados
$pdo = null;
?>
