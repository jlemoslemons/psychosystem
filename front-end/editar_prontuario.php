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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Editar Prontuário - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-edit"></i> Editar Prontuário - Sessão #<?php echo $prontuario['numero_sessao']; ?></h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong><i class="fas fa-user"></i> Paciente:</strong> <?php echo htmlspecialchars($prontuario['paciente_nome']); ?>
                </div>
                
                <form id="formEditarProntuario">
                    <input type="hidden" id="prontuario_id" value="<?php echo $prontuario['id']; ?>">
                    <input type="hidden" id="paciente_id" value="<?php echo $prontuario['paciente_id']; ?>">
                    
                    <!-- Demanda e Plano -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-comment-medical"></i> Demanda e Planejamento</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="demanda_cliente">Demanda do cliente / queixa principal</label>
                                <textarea class="form-control" id="demanda_cliente" rows="3"><?php echo htmlspecialchars($prontuario['demanda_cliente']); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="plano_terapeutico">Plano Terapêutico</label>
                                <textarea class="form-control" id="plano_terapeutico" rows="3"><?php echo htmlspecialchars($prontuario['plano_terapeutico']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informações da Sessão -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-calendar-check"></i> Informações da Sessão</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="numero_sessao">Número da sessão *</label>
                                <input type="number" class="form-control" id="numero_sessao" value="<?php echo $prontuario['numero_sessao']; ?>" min="1" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="data_sessao">Data da sessão *</label>
                                <input type="date" class="form-control" id="data_sessao" value="<?php echo $prontuario['data_sessao']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="compareceu">Compareceu? *</label>
                                <select class="form-control" id="compareceu" required>
                                    <option value="Sim" <?php echo $prontuario['compareceu'] == 'Sim' ? 'selected' : ''; ?>>Sim</option>
                                    <option value="Não" <?php echo $prontuario['compareceu'] == 'Não' ? 'selected' : ''; ?>>Não</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="justificativa_ausencia">Justificativa ausência</label>
                                <input type="text" class="form-control" id="justificativa_ausencia" value="<?php echo htmlspecialchars($prontuario['justificativa_ausencia']); ?>" <?php echo $prontuario['compareceu'] == 'Sim' ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hora_inicio">Hora início</label>
                                <input type="time" class="form-control" id="hora_inicio" value="<?php echo $prontuario['hora_inicio']; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hora_termino">Hora término</label>
                                <input type="time" class="form-control" id="hora_termino" value="<?php echo $prontuario['hora_termino']; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalidade">Modalidade</label>
                                <select class="form-control" id="modalidade">
                                    <option value="">Selecione...</option>
                                    <option value="Presencial" <?php echo $prontuario['modalidade'] == 'Presencial' ? 'selected' : ''; ?>>Presencial</option>
                                    <option value="Mediado por TIC" <?php echo $prontuario['modalidade'] == 'Mediado por TIC' ? 'selected' : ''; ?>>Mediado por TIC</option>
                                    <option value="Domiciliar" <?php echo $prontuario['modalidade'] == 'Domiciliar' ? 'selected' : ''; ?>>Domiciliar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recursos e Dados -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-clipboard-list"></i> Recursos e Observações</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="recursos_tecnicos">Recursos Técnicos utilizados</label>
                                <textarea class="form-control" id="recursos_tecnicos" rows="3"><?php echo htmlspecialchars($prontuario['recursos_tecnicos']); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="dados_relevantes">Dados mais relevantes</label>
                                <textarea class="form-control" id="dados_relevantes" rows="4"><?php echo htmlspecialchars($prontuario['dados_relevantes']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='ver_prontuario.php?id=<?php echo $prontuario['id']; ?>'">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSalvar">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="editar_prontuario.js"></script>
</body>

</html>