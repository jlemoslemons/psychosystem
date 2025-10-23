<?php
session_start();
require_once '../back-end/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados JSON
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);
    
    $nome = trim($dados['nome'] ?? '');
    $usuario = trim($dados['usuario'] ?? '');
    $senha = $dados['senha'] ?? '';
    $confirmarSenha = $dados['confirmarSenha'] ?? '';
    
    // Validações
    if (empty($nome) || empty($usuario) || empty($senha)) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Todos os campos são obrigatórios!'
        ]);
        exit;
    }
    
    if (strlen($senha) < 6) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'A senha deve ter no mínimo 6 caracteres!'
        ]);
        exit;
    }
    
    if ($senha !== $confirmarSenha) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'As senhas não coincidem!'
        ]);
        exit;
    }
    
    try {
        // Verificar se o usuário já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        
        if ($stmt->fetch()) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Usuário já existe!'
            ]);
            exit;
        }
        
        // Hash da senha com bcrypt
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        
        // Inserir novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, usuario, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $usuario, $senhaHash]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Cadastro realizado com sucesso!'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao cadastrar: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>