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
    $nome = trim($dados['nome'] ?? '');
    $data_nascimento = $dados['data_nascimento'] ?? '';
    $idade = intval($dados['idade'] ?? 0);
    $cpf = trim($dados['cpf'] ?? '');
    $rg = trim($dados['rg'] ?? '');
    $naturalidade = trim($dados['naturalidade'] ?? '');
    $sexo = $dados['sexo'] ?? '';
    $endereco = trim($dados['endereco'] ?? '');
    $cep = trim($dados['cep'] ?? '');
    $cidade = trim($dados['cidade'] ?? '');
    $contato = trim($dados['contato'] ?? '');
    $contato_emergencia = trim($dados['contato_emergencia'] ?? '');
    $escolaridade = $dados['escolaridade'] ?? '';
    $trabalha = $dados['trabalha'] ?? 'Não';
    $onde_trabalha = trim($dados['onde_trabalha'] ?? '');
    $obs = trim($dados['obs'] ?? '');
    
    // Validações
    if (empty($nome) || empty($data_nascimento) || empty($cpf) || empty($sexo) || empty($contato)) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Preencha todos os campos obrigatórios!'
        ]);
        exit;
    }
    
    try {
        // Verificar se o paciente pertence ao usuário logado
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$paciente_id, $usuario_id]);
        
        if (!$stmt->fetch()) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Paciente não encontrado!'
            ]);
            exit;
        }
        
        // Verificar se o CPF já existe em outro paciente
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE cpf = ? AND usuario_id = ? AND id != ?");
        $stmt->execute([$cpf, $usuario_id, $paciente_id]);
        
        if ($stmt->fetch()) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Já existe outro paciente cadastrado com este CPF!'
            ]);
            exit;
        }
        
        // Atualizar paciente
        $sql = "UPDATE pacientes SET 
            nome = ?, data_nascimento = ?, idade = ?, cpf = ?, rg = ?, 
            naturalidade = ?, sexo = ?, endereco = ?, cep = ?, cidade = ?, 
            contato = ?, contato_emergencia = ?, escolaridade = ?, 
            trabalha = ?, onde_trabalha = ?, obs = ?
            WHERE id = ? AND usuario_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nome, $data_nascimento, $idade, $cpf, $rg, $naturalidade,
            $sexo, $endereco, $cep, $cidade, $contato, $contato_emergencia,
            $escolaridade, $trabalha, $onde_trabalha, $obs,
            $paciente_id, $usuario_id
        ]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Paciente atualizado com sucesso!'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao atualizar paciente: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>