<?php
require 'vendor/autoload.php';
require "./conexao1.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

$arquivos = scandir('uploads/');

foreach ($arquivos as $arquivo) {
    if (is_file('uploads/' . $arquivo)) {
        $excelFile = 'uploads/' . $arquivo;
        break; // Para ao encontrar o primeiro arquivo válido
    }
}

try {
    // Carrega o arquivo Excel
    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getActiveSheet();

    $latitudeColumnIndex = -1;
    $longitudeColumnIndex = -1;
    $velocidadeColumnIndex = -1;
    $ignicaoColumnIndex = -1;
    $dataPosicaoColumnIndex = -1;

    //Itera pelas células de todas as linhas para encontrar as colunas necessárias
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $cell) {
            //Verifica o valor da célula e procura pelos valores (case-insensitive)
            $cellValue = strtolower($cell->getValue());

            if (strpos($cellValue, 'latitude') !== false && $latitudeColumnIndex == -1) {
                $latitudeColumnIndex = $cell->getColumn();    
            } elseif (strpos($cellValue, 'longitude') !== false && $longitudeColumnIndex == -1) {
                $longitudeColumnIndex = $cell->getColumn();       
            } elseif (strpos($cellValue, 'velocidade (km/h)') !== false && $velocidadeColumnIndex == -1) {
                $velocidadeColumnIndex = $cell->getColumn();            
            } elseif (strpos($cellValue, 'ignição') !== false && $ignicaoColumnIndex == -1) {
                $ignicaoColumnIndex = $cell->getColumn();
            } elseif (strpos($cellValue, 'data da posição') !== false && $dataPosicaoColumnIndex == -1) {
                $dataPosicaoColumnIndex = $cell->getColumn();  
            }
        }

        // Se as colunas forem encontradas, pare de procurar
        if ($latitudeColumnIndex !== -1 && $longitudeColumnIndex !== -1 && $velocidadeColumnIndex !== -1 && $ignicaoColumnIndex !== -1 && $dataPosicaoColumnIndex !== -1) {
            break;
        }
    }

    // Verifica se as colunas foram encontradas
    if ($latitudeColumnIndex !== -1 && $longitudeColumnIndex !== -1 && $velocidadeColumnIndex !== -1 && $ignicaoColumnIndex !== -1 && $dataPosicaoColumnIndex !== -1) {
        // Itera pelas linhas para obter os dados e inseri-los no banco de dados
        foreach ($sheet->getRowIterator() as $row) {
            $numeroLinha = $row->getRowIndex();
            
            $latitude = $sheet->getCell($latitudeColumnIndex . $row->getRowIndex())->getValue();
            $longitude = $sheet->getCell($longitudeColumnIndex . $row->getRowIndex())->getValue();
            $velocidade = $sheet->getCell($velocidadeColumnIndex . $row->getRowIndex())->getValue();
            $ignicao = $sheet->getCell($ignicaoColumnIndex . $row->getRowIndex())->getValue();
            $dataPosicaoFloat = $sheet->getCell($dataPosicaoColumnIndex . $row->getRowIndex())->getValue();
            
            if ($latitude == "Latitude" || $dataPosicaoFloat == null) {
                continue;
            }
                     
            $days = floor($dataPosicaoFloat);
            $fraction = $dataPosicaoFloat - $days;
            $seconds = floor(86400 * $fraction);
            $date = date('Y-m-d H:i:s', strtotime("1899-12-30 + $days days + $seconds seconds"));
            
            // Separa a string em data e hora usando espaço como delimitador
            $partes = explode(' ', $date);
            
            // $partes[0] conterá a data e $partes[1] conterá a hora
            
            $data = $partes[0];
            $hora = $partes[1];

            if ($ignicao === "OFF") {
                $ignicao = 0;
            } else {
                $ignicao = 1;
            }  
            
            $latitude = str_replace(",", ".", $latitude);
            $longitude = str_replace(",", ".", $longitude);
            $velocidade = str_replace(",", ".", $velocidade);
            
            if (!empty($latitude) && !empty($longitude) && !empty($dataPosicaoFloat)){
                // Insira os dados no banco de dados 
                $sql = $pdo->prepare("INSERT INTO coordenada (latitude, longitude, velocidade, ignicao, data, hora, numeroLinha) VALUES (:latitude, :longitude, :velocidade, :ignicao, :data, :hora, :numeroLinha)");
                $sql->bindParam(':latitude', $latitude);
                $sql->bindParam(':longitude', $longitude);
                $sql->bindParam(':velocidade', $velocidade);
                $sql->bindParam(':ignicao', $ignicao);
                $sql->bindParam(':data', $data);
                $sql->bindParam(':hora', $hora);
                $sql->bindParam(':numeroLinha', $numeroLinha);
                $sql->execute();
            }
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Colunas não encontradas no arquivo Excel.\n";
        echo "</div> ";
        echo "<button class='btn btn-warning' onclick='voltarPagina()'>Voltar</button>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo "Erro ao processar o arquivo Excel: " . $e->getMessage() . "\n";
    echo "</div> ";
    echo "<button class='btn btn-warning' onclick='voltarPagina()'>Voltar</button>";
}

// Feche a conexão com o banco de dados
$pdo = null;

echo "<div class='d-flex justify-content-evenly'>";
echo "<button class='btn btn-warning' onclick='voltarPagina()'>Voltar</button>";
echo "<a class='btn btn-success' href='conexao.php'>Avançar</a>";
echo "</div>";
header("Location: ponto_index.php");
exit();
