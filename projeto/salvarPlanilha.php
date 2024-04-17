<?php
require "./conexao.php";
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/sistema/landpage.css" rel="stylesheet">
    <link href="css/sistema/login.css" rel="stylesheet">
</head>

<body>
    <div class="container">
    <?php
        // Verifica se o arquivo foi enviado sem erros
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            // Informações sobre o arquivo enviado
            $nomePlanilha = $_FILES['arquivo']['name'];
            
            $extensao = pathinfo($nomePlanilha, PATHINFO_EXTENSION);
            $caminho_temporario = $_FILES['arquivo']['tmp_name'];

            if ($extensao !== 'xls' && $extensao !== 'xlsx') {
                echo "<div class='alert alert-primary' role='alert'>";
                echo "Por favor, selecione um arquivo do Excel (.xls ou .xlsx).";
                echo "</div> ";
                echo "<a class='btn btn-warning' href='menu.php'>Voltar</a>";
            } else {
                // Mova o arquivo enviado para o local desejado               
                $destino = 'uploads/' . $nomePlanilha; //Pasta onde o arquivo será armazenado
                if (move_uploaded_file($caminho_temporario, $destino)) {
                    echo "<div class='alert alert-success' role='alert'>";
                    echo "O arquivo $nomePlanilha foi enviado com sucesso.";
                    echo "</div> ";
                    echo "<div class='d-flex justify-content-evenly'>";
                    echo "<a href='menu.php' class='btn btn-warning'>Voltar</a>";
                    echo "<a class='btn btn-success' href='processarPlanilha.php'>Avançar</a>";
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "Erro ao enviar o arquivo.";
                    echo "</div> ";
                    echo "<a href='menu.php' class='btn btn-warning'>Voltar</a>";
                }
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