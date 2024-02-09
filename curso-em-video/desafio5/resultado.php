<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio 5 PHP</title>
    <link rel="stylesheet" href="../ex004/style.css">
</head>
<body>
    <main>
        <h1>Analisador de Número Real</h1>
        <?php
            $numero = $_GET["numeroReal"] ?? 0;
            $parteInteira = (int) $numero;
            $parteFracionaria = $numero-$parteInteira;

            echo "<p>Analisando o número <strong>" . number_format($numero, 3, ",", ".") . "</strong> informado pelo usuário:</p>";
            echo "
            <ul>
                <li>A parte inteira do número é <strong>" . number_format($parteInteira, 0, ",", ".") . "</strong></li>
                <li>A parte fracionária do número é <strong>" . number_format($parteFracionaria, 3, ",", ".") . "</strong></li>
            </ul>"
        ?>
        <button onclick="javascript:history.go(-1)">Voltar</button>
    </main>
</body>
</html>