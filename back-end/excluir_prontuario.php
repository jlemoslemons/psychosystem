<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Usuário não autenticado!'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    
    $usuario_id = $_SESSION['usuario_id'];
    $prontuario_id = intval($dados['id'] ?? 0);
    
    if ($prontuario_id <= 0) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'ID do prontuário inválido!'
        ]);
        exit;
    }
    
    try {
        // Verificar se o prontuário pertence ao usuário logado
        $stmt = $pdo->prepare("SELECT numero_sessao FROM prontuarios WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$prontuario_id, $usuario_id]);
        $prontuario = $stmt->fetch();
        
        if (!$prontuario) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Prontuário não encontrado!'
            ]);
            exit;
        }
        
        // Excluir prontuário
        $stmt = $pdo->prepare("DELETE FROM prontuarios WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$prontuario_id, $usuario_id]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Prontuário excluído com sucesso!'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao excluir prontuário: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>