<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Formulário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Resultado</h1>
    </header> 
    <main>
        <?php
            $nome = $_GET["nome"] ?? "sem nome";
            $sobrenome = $_GET["sobrenome"] ?? "desconhecido";
            echo "<p>Bem vindo <strong>$nome</strong> <strong>$sobrenome</strong>, esse é seu site!</p>";
        ?>
        <button onclick="javascript:history.go(-1)">Voltar para a página anterior</button>
    </main>
</body>
</html>