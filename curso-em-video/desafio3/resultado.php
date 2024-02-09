<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio 3 PHP</title>
    <link rel="stylesheet" href="../ex004/style.css">
</head>

<body>
    <main>
        <h1>Conversor de Moedas v1.0</h1>
        <?php
            $dinheiro = $_GET["numeroReal"] ?? 0;
            $cotacaoDolar = 4.89;
            $conversao = $dinheiro / $cotacaoDolar;

            $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);

            echo '<p>Seus ' . numfmt_format_currency($padrao, $dinheiro, 'BRL') . ' equivalem a <strong>' . numfmt_format_currency($padrao, $conversao, 'USD') .  '</strong></p>';

            echo '<p><strong>*Cotação fixa de ' . numfmt_format_currency($padrao, $cotacaoDolar, 'BRL') . '</strong> informada diretamento no código.</p>';
        ?>
        <button onclick="javascript:history.go(-1)">Voltar</button>
    </main>
</body>

</html>