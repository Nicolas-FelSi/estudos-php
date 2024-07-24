<?php

$host = 'localhost';
$dbname = 'projeto';
$username = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<div class='alert alert-primary' role='alert'>";
    echo "Erro de conexão com o banco de dados: " . $e->getMessage() . "\n";
    echo "</div> ";
    echo "<button class='btn btn-warning' onclick='voltarPagina()'>Voltar</button>";
    exit();
}
 ?>