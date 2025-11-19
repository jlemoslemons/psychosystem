<?php
session_start();
require_once '../back-end/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Pegar ID do paciente da URL
$paciente_id = $_GET['paciente_id'] ?? 0;

// Buscar dados do paciente (verificar se pertence ao usuário)
try {
    $stmt = $pdo->prepare("SELECT id, nome FROM pacientes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$paciente_id, $usuario_id]);
    $paciente = $stmt->fetch();
    
    if (!$paciente) {
        header('Location: listar_pacientes.php');
        exit;
    }
    
    // Buscar número da próxima sessão
    $stmt = $pdo->prepare("SELECT MAX(numero_sessao) as ultima_sessao FROM prontuarios WHERE paciente_id = ? AND usuario_id = ?");
    $stmt->execute([$paciente_id, $usuario_id]);
    $resultado = $stmt->fetch();
    $proxima_sessao = ($resultado['ultima_sessao'] ?? 0) + 1;
    
} catch (PDOException $e) {
    $erro = "Erro ao buscar paciente: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Novo Prontuário - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-medical-alt"></i> Novo Prontuário</h3>
                <div>
                    <button class="btn btn-secondary" onclick="window.location.href='ver_paciente.php?id=<?php echo $paciente_id; ?>'">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php else: ?>
                
                <div class="alert alert-info">
                    <strong><i class="fas fa-user"></i> Paciente:</strong> <?php echo htmlspecialchars($paciente['nome']); ?>
                </div>
                
                <form id="formProntuario">
                    <input type="hidden" id="paciente_id" value="<?php echo $paciente_id; ?>">
                    
                    <!-- Demanda e Plano -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-comment-medical"></i> Demanda e Planejamento</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="demanda_cliente">Demanda do cliente / queixa principal</label>
                                <textarea class="form-control" id="demanda_cliente" rows="3" placeholder="Descreva a demanda ou queixa principal do cliente..."></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="plano_terapeutico">Plano Terapêutico</label>
                                <textarea class="form-control" id="plano_terapeutico" rows="3" placeholder="Descreva o plano terapêutico..."></textarea>
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
                                <input type="number" class="form-control" id="numero_sessao" value="<?php echo $proxima_sessao; ?>" min="1" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="data_sessao">Data da sessão *</label>
                                <input type="date" class="form-control" id="data_sessao" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="compareceu">Compareceu? *</label>
                                <select class="form-control" id="compareceu" required>
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="justificativa_ausencia">Justificativa ausência</label>
                                <input type="text" class="form-control" id="justificativa_ausencia" placeholder="Preencher se não compareceu" disabled>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hora_inicio">Hora início</label>
                                <input type="time" class="form-control" id="hora_inicio">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hora_termino">Hora término</label>
                                <input type="time" class="form-control" id="hora_termino">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalidade">Modalidade</label>
                                <select class="form-control" id="modalidade">
                                    <option value="">Selecione...</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Mediado por TIC">Mediado por TIC</option>
                                    <option value="Domiciliar">Domiciliar</option>
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
                                <textarea class="form-control" id="recursos_tecnicos" rows="3" placeholder="Descreva os recursos técnicos utilizados na sessão..."></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="dados_relevantes">Dados mais relevantes</label>
                                <textarea class="form-control" id="dados_relevantes" rows="4" placeholder="Descreva os dados mais relevantes da sessão..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='ver_paciente.php?id=<?php echo $paciente_id; ?>'">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSalvar">
                            <i class="fas fa-save"></i> Salvar Prontuário
                        </button>
                    </div>
                </form>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="prontuarios.js"></script>
</body>

</html>