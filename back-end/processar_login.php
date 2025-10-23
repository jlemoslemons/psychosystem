<?php
session_start();
require_once 'back-end/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados JSON
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    
    $usuario = trim($dados['usuario'] ?? '');
    $senha = $dados['senha'] ?? '';
    
    // Validações
    if (empty($usuario) || empty($senha)) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Usuário e senha são obrigatórios!'
        ]);
        exit;
    }
    
    try {
        // Buscar usuário
        $stmt = $pdo->prepare("SELECT id, nome, usuario, senha FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $usuarioDB = $stmt->fetch();
        
        // Verificar se usuário existe e senha está correta
        if ($usuarioDB && password_verify($senha, $usuarioDB['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuarioDB['id'];
            $_SESSION['usuario_nome'] = $usuarioDB['nome'];
            $_SESSION['usuario_usuario'] = $usuarioDB['usuario'];
            
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Login realizado com sucesso!',
                'redirect' => 'front-end/visao_geral.php',
                'usuario' => [
                    'id' => $usuarioDB['id'],
                    'nome' => $usuarioDB['nome'],
                    'usuario' => $usuarioDB['usuario']
                ]
            ]);
        } else {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Usuário ou senha inválidos!'
            ]);
        }
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao processar login: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>