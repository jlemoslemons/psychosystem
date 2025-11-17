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
    // Receber dados JSON
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    $usuario_id = $_SESSION['usuario_id'];
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

    // Validar CPF (formato básico)
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf_limpo) != 11) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'CPF inválido!'
        ]);
        exit;
    }

    try {
        // Verificar se CPF já existe para este usuário
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE cpf = ? AND usuario_id = ?");
        $stmt->execute([$cpf, $usuario_id]);

        if ($stmt->fetch()) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Já existe um paciente cadastrado com este CPF!'
            ]);
            exit;
        }

        // Inserir paciente
        $sql = "INSERT INTO pacientes (
            usuario_id, nome, data_nascimento, idade, cpf, rg, naturalidade, 
            sexo, endereco, cep, cidade, contato, contato_emergencia, 
            escolaridade, trabalha, onde_trabalha, obs
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $usuario_id,
            $nome,
            $data_nascimento,
            $idade,
            $cpf,
            $rg,
            $naturalidade,
            $sexo,
            $endereco,
            $cep,
            $cidade,
            $contato,
            $contato_emergencia,
            $escolaridade,
            $trabalha,
            $onde_trabalha,
            $obs
        ]);

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Paciente cadastrado com sucesso!',
            'paciente_id' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao cadastrar paciente: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
