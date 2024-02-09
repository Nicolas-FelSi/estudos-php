<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado Desafio PHP</title>
    <link rel="stylesheet" href="../ex004/style.css">
</head>
<body>
    <main>
        <h1>Resultado Final</h1>
        <?php
            $numeroEscolhido = $_GET["numero"] ?? 0;
            $antecessor = $numeroEscolhido - 1;
            $sucessor = $numeroEscolhido + 1;

            echo "O número escolhido foi <strong>$numeroEscolhido</strong><br>";
            echo "O seu <em>antecessor</em> é $antecessor<br>";
            echo "O seu <em>sucessor</em> é $sucessor<br>";
        ?>
        <button onclick="javascript:history.go(-1)">&#129044; Voltar</button>
    </main>
</body>
</html>