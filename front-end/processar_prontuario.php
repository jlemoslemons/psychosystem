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
    $paciente_id = intval($dados['paciente_id'] ?? 0);
    $demanda_cliente = trim($dados['demanda_cliente'] ?? '');
    $plano_terapeutico = trim($dados['plano_terapeutico'] ?? '');
    $numero_sessao = intval($dados['numero_sessao'] ?? 0);
    $data_sessao = $dados['data_sessao'] ?? '';
    $compareceu = $dados['compareceu'] ?? 'Sim';
    $justificativa_ausencia = trim($dados['justificativa_ausencia'] ?? '');
    $hora_inicio = $dados['hora_inicio'] ?? null;
    $hora_termino = $dados['hora_termino'] ?? null;
    $modalidade = $dados['modalidade'] ?? null;
    $recursos_tecnicos = trim($dados['recursos_tecnicos'] ?? '');
    $dados_relevantes = trim($dados['dados_relevantes'] ?? '');
    
    // Validações
    if ($paciente_id <= 0 || $numero_sessao <= 0 || empty($data_sessao)) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Preencha todos os campos obrigatórios!'
        ]);
        exit;
    }
    
    // Se não compareceu, justificativa é obrigatória
    if ($compareceu === 'Não' && empty($justificativa_ausencia)) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Justificativa de ausência é obrigatória quando o paciente não comparece!'
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
        
        // Verificar se já existe prontuário com esse número de sessão para esse paciente
        $stmt = $pdo->prepare("SELECT id FROM prontuarios WHERE paciente_id = ? AND usuario_id = ? AND numero_sessao = ?");
        $stmt->execute([$paciente_id, $usuario_id, $numero_sessao]);
        
        if ($stmt->fetch()) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Já existe um prontuário com esse número de sessão para este paciente!'
            ]);
            exit;
        }
        
        // Converter horas vazias para NULL
        $hora_inicio = !empty($hora_inicio) ? $hora_inicio : null;
        $hora_termino = !empty($hora_termino) ? $hora_termino : null;
        $modalidade = !empty($modalidade) ? $modalidade : null;
        
        // Inserir prontuário
        $sql = "INSERT INTO prontuarios (
            usuario_id, paciente_id, demanda_cliente, plano_terapeutico,
            numero_sessao, data_sessao, compareceu, justificativa_ausencia,
            hora_inicio, hora_termino, modalidade, recursos_tecnicos, dados_relevantes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $usuario_id, $paciente_id, $demanda_cliente, $plano_terapeutico,
            $numero_sessao, $data_sessao, $compareceu, $justificativa_ausencia,
            $hora_inicio, $hora_termino, $modalidade, $recursos_tecnicos, $dados_relevantes
        ]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Prontuário salvo com sucesso!',
            'prontuario_id' => $pdo->lastInsertId()
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao salvar prontuário: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido!'
    ]);
}
?>