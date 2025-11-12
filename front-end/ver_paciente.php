<?php
session_start();
require_once '../back-end/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$paciente_id = $_GET['id'] ?? 0;

// Buscar dados do paciente (apenas se pertencer ao usuário logado)
try {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$paciente_id, $usuario_id]);
    $paciente = $stmt->fetch();
    
    if (!$paciente) {
        header('Location: listar_pacientes.php');
        exit;
    }
} catch (PDOException $e) {
    $erro = "Erro ao buscar paciente: " . $e->getMessage();
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Detalhes do Paciente - PsychoSystem</title>
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
                <h3><i class="fas fa-user-circle"></i> Detalhes do Paciente</h3>
                <div>
                    <button class="btn btn-warning" onclick="window.location.href='editar_paciente.php?id=<?php echo $paciente['id']; ?>'">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='listar_pacientes.php'">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php else: ?>
                
                <!-- Dados Pessoais -->
                <h4 class="section-title"><i class="fas fa-user"></i> Dados Pessoais</h4>
                <div class="row">
                    <div class="col-md-8">
                        <div class="info-group">
                            <div class="info-label">Nome Completo</div>
                            <div class="info-value"><?php echo exibir($paciente['nome']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-group">
                            <div class="info-label">Data de Nascimento</div>
                            <div class="info-value">
                                <?php 
                                if (!empty($paciente['data_nascimento'])) {
                                    echo date('d/m/Y', strtotime($paciente['data_nascimento']));
                                } else {
                                    echo '<span class="text-muted">Não informado</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Idade</div>
                            <div class="info-value"><?php echo exibir($paciente['idade'] . ' anos'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group">
                            <div class="info-label">CPF</div>
                            <div class="info-value"><?php echo exibir($paciente['cpf']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group">
                            <div class="info-label">RG</div>
                            <div class="info-value"><?php echo exibir($paciente['rg']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Sexo</div>
                            <div class="info-value"><?php echo exibir($paciente['sexo']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Naturalidade</div>
                            <div class="info-value"><?php echo exibir($paciente['naturalidade']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Endereço -->
                <h4 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereço</h4>
                <div class="row">
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">CEP</div>
                            <div class="info-value"><?php echo exibir($paciente['cep']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="info-group">
                            <div class="info-label">Endereço</div>
                            <div class="info-value"><?php echo exibir($paciente['endereco']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-group">
                            <div class="info-label">Cidade</div>
                            <div class="info-value"><?php echo exibir($paciente['cidade']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Contato -->
                <h4 class="section-title"><i class="fas fa-phone"></i> Contato</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Contato</div>
                            <div class="info-value"><?php echo exibir($paciente['contato']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Contato de Emergência</div>
                            <div class="info-value"><?php echo exibir($paciente['contato_emergencia']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Informações Profissionais -->
                <h4 class="section-title"><i class="fas fa-briefcase"></i> Informações Profissionais e Acadêmicas</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-group">
                            <div class="info-label">Escolaridade</div>
                            <div class="info-value"><?php echo exibir($paciente['escolaridade']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="info-group">
                            <div class="info-label">Trabalha?</div>
                            <div class="info-value"><?php echo exibir($paciente['trabalha']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Onde Trabalha</div>
                            <div class="info-value"><?php echo exibir($paciente['onde_trabalha']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Observações -->
                <h4 class="section-title"><i class="fas fa-sticky-note"></i> Observações</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-group">
                            <div class="info-value" style="min-height: 100px; white-space: pre-wrap;">
                                <?php echo exibir($paciente['obs']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Data de Cadastro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <p class="text-muted">
                            <small>
                                <i class="fas fa-clock"></i> 
                                Cadastrado em: <?php echo date('d/m/Y H:i', strtotime($paciente['data_cadastro'])); ?>
                                <?php if ($paciente['data_atualizacao'] != $paciente['data_cadastro']): ?>
                                    | Última atualização: <?php echo date('d/m/Y H:i', strtotime($paciente['data_atualizacao'])); ?>
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