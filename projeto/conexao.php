<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Conexão com o banco de dados MySQL usando PDO
$host = 'localhost';
$dbname = 'projeto';
$username = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro de conexão com o banco de dados: " . $e->getMessage() . "\n";
    exit();
}

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

    // Itera pelas células de todas as linhas para encontrar as colunas de latitude e longitude
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $cell) {
            // Verifica o valor da célula e procura por "latitude" e "longitude" (case-insensitive)
            $cellValue = strtolower($cell->getValue());

            if (strpos($cellValue, 'latitude') !== false && $latitudeColumnIndex == -1) {
                $latitudeColumnIndex = $cell->getColumn();
            } elseif (strpos($cellValue, 'longitude') !== false && $longitudeColumnIndex == -1) {
                $longitudeColumnIndex = $cell->getColumn();
            }
        }

        // Se as colunas de latitude e longitude forem encontradas, pare de procurar
        if ($latitudeColumnIndex !== -1 && $longitudeColumnIndex !== -1) {
            break;
        }
    }

    // Verifica se as colunas foram encontradas
    if ($latitudeColumnIndex !== -1 && $longitudeColumnIndex !== -1) {
        // Itera pelas linhas para obter os dados de latitude e longitude e inseri-los no banco de 
        foreach ($sheet->getRowIterator() as $row) { 
            $latitude = $sheet->getCell($latitudeColumnIndex . $row->getRowIndex())->getValue();
            $latitude = str_replace(",", ".", $latitude);
            $longitude = $sheet->getCell($longitudeColumnIndex . $row->getRowIndex())->getValue();
            $longitude = str_replace(",", ".", $longitude);

            if (!empty($latitude) && !empty($longitude)) {
                // Insira os dados no banco de dados
                $stmt = $pdo->prepare("INSERT INTO coordenadas (latitude, longitude) VALUES (:latitude, :longitude)");
                $stmt->bindParam(':latitude', $latitude);
                $stmt->bindParam(':longitude', $longitude);
                $stmt->execute();
            }
        }
    } else {
        echo "Colunas de latitude e/ou longitude não encontradas no arquivo Excel.\n";
    }
} catch (Exception $e) {
    echo "Erro ao processar o arquivo Excel: " . $e->getMessage() . "\n";
}

// Feche a conexão com o banco de dados
$pdo = null;

header("Location: mapa.html");
exit();
?>









