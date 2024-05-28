<?php
require "./conexao.php";
session_start();
ob_start();

if (!(isset($_SESSION['id_usuario']) && isset($_SESSION['nome']))) {
  header("Location: login.php");
  exit();
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
   <!-- <link href="css/sistema/landpage.css" rel="stylesheet"> -->
    <link href="css/sistema/login.css" rel="stylesheet">
</head>

<body>
    <div class="container">
    <?php
        // Verifica se o arquivo foi enviado sem erros
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            // Informações sobre o arquivo enviado            
            $nomePlanilha = $_FILES['arquivo']['name'];    
            $file_tmp = $_FILES['arquivo']['tmp_name']; 
            $extensao = pathinfo($nomePlanilha, PATHINFO_EXTENSION);

            if ($extensao !== 'xls' && $extensao !== 'xlsx') {
                echo "<div class='alert alert-primary' role='alert'>";
                echo "Por favor, selecione um arquivo do Excel (.xls ou .xlsx).";
                echo "</div> ";
                echo "<a class='btn btn-warning' href='menu.php'>Voltar</a>";
            } else {
                // Arquivo não existe, fazer o upload
                move_uploaded_file($file_tmp, "uploads/" . $nomePlanilha);

                $idUsuario = $_SESSION['id_usuario'];                     
                
                // Insere o nome do arquivo no banco de dados
                $sql = $pdo->prepare("INSERT INTO planilha (nome_planilha, fk_id_usuario) VALUES (:nome_planilha, :fk_id_usuario)");
                $sql->bindParam(':nome_planilha', $nomePlanilha);
                $sql->bindParam(':fk_id_usuario', $idUsuario);
                $sql->execute();          
                
                $sql = $pdo->prepare("SELECT id_planilha FROM planilha WHERE fk_id_usuario = :fk_id_usuario");
                $sql->bindParam(':fk_id_usuario', $idUsuario);
                $sql->execute();  
                
                $resultado = $sql->fetch(PDO::FETCH_ASSOC);
                $_SESSION['id_planilha'] = $resultado['id_planilha'];

                header("Location: processarPlanilha.php");
                
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>";
            echo "Erro no envio do arquivo.";
            echo "</div> ";
            echo "<a class='btn btn-warning' href='menu.php'>Voltar</a>";
        }
    ?>
    </div>

    <script src="./js/jquery/jquery-3.7.1.min.js"></script>
    <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/51b23194c0.js" crossorigin="anonymous"></script>
</body>

</html>