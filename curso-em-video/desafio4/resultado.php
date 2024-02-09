<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio 4 PHP</title>
    <link rel="stylesheet" href="../ex004/style.css">
</head>
<body>
    <main>
        <h1>Conversor de Moedas</h1>
        <?php
            $inicio = date('m-d-Y', strtotime('-7 days'));
            $fim = date('m-d-Y');          
            $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\''. $inicio .'\'&@dataFinalCotacao=\''. $fim .'\'&$top=1&$orderby=dataHoraCotacao&$format=json&$select=cotacaoCompra,dataHoraCotacao';
            $dados = json_decode(file_get_contents($url), true);
            $cotacao = $dados["value"][0]["cotacaoCompra"];

            $reais = $_GET['reais'] ?? 0;
            $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);
            $conversao = $reais/$cotacao;

            echo '<p>Seus ' . numfmt_format_currency($padrao, $reais, 'BRL') . ' equivalem a <strong>' . numfmt_format_currency($padrao, $conversao, 'USD') . '</strong></p>';
        
        ?>
        <p>*Cotação obtida diretamente do site do <strong>Banco Central do Brasil</strong></p>
        <button onclick="javascript:history.go(-1)">Voltar</button>
    </main>
</body>
</html>