<?php
function deslogado() {
    echo "<div class='alert alert-success text-center' role='alert'>";
    echo "Deslogado com sucesso!";
    echo "</div>";
}

session_start();
ob_start();
unset($_SESSION['idUsuario'], $_SESSION['nome']);
$_SESSION['msg'] = deslogado();
header("Location: login.php");