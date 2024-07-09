<?php

session_start();
ob_start();
unset($_SESSION['id_usuario'], $_SESSION['nome'], $_SESSION['id_planilha']);
header("Location: index.php");
exit();