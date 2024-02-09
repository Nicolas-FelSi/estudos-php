<?php
// Verifica se o arquivo foi enviado sem erros
if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    // Informações sobre o arquivo enviado
    $nome_arquivo = $_FILES['arquivo']['name'];
    $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
    $caminho_temporario = $_FILES['arquivo']['tmp_name'];

    if ($extensao !== 'xls' && $extensao !== 'xlsx') {
        echo "Por favor, selecione um arquivo do Excel (.xls ou .xlsx).";
    } else {
        // Processa o arquivo do Excel... 

        // Mova o arquivo enviado para o local desejado
        $destino = 'uploads/' . $nome_arquivo;  //Pasta onde o arquivo será armazenado
        if (move_uploaded_file($caminho_temporario, $destino)) {
            echo "O arquivo $nome_arquivo foi enviado com sucesso.";

            echo "<form action='conexao.php' method='post'>";
            echo "  <input type='submit' value='Prosseguir'>";
            echo "</form>";
        } else {
            echo "Erro ao enviar o arquivo.";
        }
    }
} else {
    echo "Erro no envio do arquivo.";
}
