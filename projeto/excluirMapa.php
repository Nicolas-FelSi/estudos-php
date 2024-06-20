<?php
require './conexao.php';

try {
    if (isset($_GET['id_planilha'])) {
        $id_planilha = intval($_GET['id_planilha']);
        
        // Iniciar transação
        $pdo->beginTransaction();
        
        try {
            // Primeiro, excluir da tabela 'coordenada'
            $sql = 'DELETE FROM coordenada WHERE fk_id_planilha = :id_planilha';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_planilha', $id_planilha, PDO::PARAM_INT);
            $stmt->execute();
            
            // Depois, excluir da tabela 'planilha'
            $sql = 'DELETE FROM planilha WHERE id_planilha = :id_planilha';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_planilha', $id_planilha, PDO::PARAM_INT);
            $stmt->execute();
            
            // Commit da transação
            $pdo->commit();
            
            // Redirecionar de volta para a página principal após a exclusão
            header("Location: menu.php");
            exit();
        } catch (Exception $e) {
            // Rollback da transação em caso de erro
            $pdo->rollBack();
            echo 'Erro ao excluir os registros: ' . $e->getMessage();
        }
    } else {
        echo "ID não encontrado.";
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>

