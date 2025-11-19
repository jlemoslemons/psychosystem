<?php
session_start();
require_once '../back-end/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$prontuario_id = $_GET['id'] ?? 0;

// Buscar prontuário (apenas se pertencer ao usuário logado)
try {
    $stmt = $pdo->prepare("
        SELECT p.*, pac.nome as paciente_nome 
        FROM prontuarios p
        INNER JOIN pacientes pac ON p.paciente_id = pac.id
        WHERE p.id = ? AND p.usuario_id = ?
    ");
    $stmt->execute([$prontuario_id, $usuario_id]);
    $prontuario = $stmt->fetch();
    
    if (!$prontuario) {
        header('Location: listar_pacientes.php');
        exit;
    }
} catch (PDOException $e) {
    $erro = "Erro ao buscar prontuário: " . $e->getMessage();
}

// Função para exibir valor ou "Não informado"
function exibir($valor) {
    return !empty($valor) ? htmlspecialchars($valor) : '<span class="text-muted">Não informado</span>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Visualizar Prontuário - PsychoSystem</title>
    <style>
        .info-group {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .info-value {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #007bff;
            white-space: pre-wrap;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            color: #007bff;
        }
    </style>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-medical-alt"></i> Prontuário - Sessão #<?php echo $prontuario['numero_sessao']; ?></h3>
                <div>
                    <button class="btn btn-warning" onclick="window.location.href='editar_prontuario.php?id=<?php echo $prontuario['id']; ?>'">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='listar_prontuarios.php?paciente_id=<?php echo $prontuario['paciente_id']; ?>'">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php else: ?>
                
                <!-- Informações do Paciente -->
                <div class="alert alert-info">
                    <strong><i class="fas fa-user"></i> Paciente:</strong> <?php echo htmlspecialchars($prontuario['paciente_nome']); ?>
                </div>
                
                <!-- Demanda e Plano -->
                <h4 class="section-title"><i class="fas fa-comment-medical"></i> Demanda e Planejamento</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-group">
                            <div class="info-label">Demanda do cliente / queixa principal</div>
                            <div class="info-value"><?php echo exibir($prontuario['demanda_cliente']); ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="info-group">
                            <div class="info-label">Plano Terapêutico</div>
                            <div class="info-value"><?php echo exibir($prontuario['plano_terapeutico']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Informações da Sessão -->
                <h4 class="section-title"><i class="fas fa-calendar-check"></i> Informações da Sessão</h4>
                <div class="row">
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Número da sessão</div>
                            <div class="info-value"><?php echo $prontuario['numero_sessao']; ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-group">
                            <div class="info-label">Data da sessão</div>
                            <div class="info-value">
                                <?php echo date('d/m/Y', strtotime($prontuario['data_sessao'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Compareceu?</div>
                            <div class="info-value">
                                <?php if ($prontuario['compareceu'] === 'Sim'): ?>
                                    <span class="badge badge-success">Sim</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Não</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="info-group">
                            <div class="info-label">Justificativa ausência</div>
                            <div class="info-value"><?php echo exibir($prontuario['justificativa_ausencia']); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Hora início</div>
                            <div class="info-value">
                                <?php echo $prontuario['hora_inicio'] ? date('H:i', strtotime($prontuario['hora_inicio'])) : '<span class="text-muted">Não informado</span>'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Hora término</div>
                            <div class="info-value">
                                <?php echo $prontuario['hora_termino'] ? date('H:i', strtotime($prontuario['hora_termino'])) : '<span class="text-muted">Não informado</span>'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-group">
                            <div class="info-label">Duração</div>
                            <div class="info-value">
                                <?php 
                                if ($prontuario['hora_inicio'] && $prontuario['hora_termino']) {
                                    $inicio = strtotime($prontuario['hora_inicio']);
                                    $fim = strtotime($prontuario['hora_termino']);
                                    $duracao = ($fim - $inicio) / 60; // em minutos
                                    echo $duracao . ' minutos';
                                } else {
                                    echo '<span class="text-muted">Não calculável</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="info-group">
                            <div class="info-label">Modalidade</div>
                            <div class="info-value"><?php echo exibir($prontuario['modalidade']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Recursos e Dados -->
                <h4 class="section-title"><i class="fas fa-clipboard-list"></i> Recursos e Observações</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-group">
                            <div class="info-label">Recursos Técnicos utilizados</div>
                            <div class="info-value"><?php echo exibir($prontuario['recursos_tecnicos']); ?></div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="info-group">
                            <div class="info-label">Dados mais relevantes</div>
                            <div class="info-value"><?php echo exibir($prontuario['dados_relevantes']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Data de Cadastro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <p class="text-muted">
                            <small>
                                <i class="fas fa-clock"></i> 
                                Cadastrado em: <?php echo date('d/m/Y H:i', strtotime($prontuario['data_cadastro'])); ?>
                                <?php if ($prontuario['data_atualizacao'] != $prontuario['data_cadastro']): ?>
                                    | Última atualização: <?php echo date('d/m/Y H:i', strtotime($prontuario['data_atualizacao'])); ?>
                                <?php endif; ?>
                            </small>
                        </p>
                    </div>
                </div>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>