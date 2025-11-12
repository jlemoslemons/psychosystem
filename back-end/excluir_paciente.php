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
    $paciente_id = intval($dados['id'] ?? 0);
    
    if ($paciente_id <= 0) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'ID do paciente inválido!'
        ]);
        exit;
    }
    
    try {
        // Verificar se o paciente pertence ao usuário logado
        $stmt = $pdo->prepare("SELECT nome FROM pacientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$paciente_id, $usuario_id]);
        $paciente = $stmt->fetch();
        
        if (!$paciente) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Paciente não encontrado!'
            ]);
            exit;
        }
        
        // Excluir paciente
        $stmt = $pdo->prepare("DELETE FROM pacientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$paciente_id, $usuario_id]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Paciente excluído com sucesso!'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao excluir paciente: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>