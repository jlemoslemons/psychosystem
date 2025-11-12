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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Editar Paciente - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-user-edit"></i> Editar Paciente</h3>
            </div>
            <div class="card-body">
                <form id="formEditarPaciente">
                    <input type="hidden" id="paciente_id" value="<?php echo $paciente['id']; ?>">
                    
                    <div class="row">
                        <!-- Dados Pessoais -->
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-user"></i> Dados Pessoais</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" value="<?php echo htmlspecialchars($paciente['nome']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="data_nascimento">Data de Nascimento *</label>
                                <input type="date" class="form-control" id="data_nascimento" value="<?php echo $paciente['data_nascimento']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="idade">Idade *</label>
                                <input type="number" class="form-control" id="idade" value="<?php echo $paciente['idade']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cpf">CPF *</label>
                                <input type="text" class="form-control" id="cpf" value="<?php echo htmlspecialchars($paciente['cpf']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rg">RG</label>
                                <input type="text" class="form-control" id="rg" value="<?php echo htmlspecialchars($paciente['rg']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="naturalidade">Naturalidade</label>
                                <input type="text" class="form-control" id="naturalidade" value="<?php echo htmlspecialchars($paciente['naturalidade']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sexo">Sexo *</label>
                                <select class="form-control" id="sexo" required>
                                    <option value="">Selecione...</option>
                                    <option value="Masculino" <?php echo $paciente['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="Feminino" <?php echo $paciente['sexo'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                                    <option value="Outro" <?php echo $paciente['sexo'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
                                    <option value="Prefiro não informar" <?php echo $paciente['sexo'] == 'Prefiro não informar' ? 'selected' : ''; ?>>Prefiro não informar</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Endereço -->
                        <div class="col-md-12 mt-3">
                            <h5 class="text-primary"><i class="fas fa-map-marker-alt"></i> Endereço</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control" id="cep" value="<?php echo htmlspecialchars($paciente['cep']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <input type="text" class="form-control" id="endereco" value="<?php echo htmlspecialchars($paciente['endereco']); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" id="cidade" value="<?php echo htmlspecialchars($paciente['cidade']); ?>">
                            </div>
                        </div>
                        
                        <!-- Contato -->
                        <div class="col-md-12 mt-3">
                            <h5 class="text-primary"><i class="fas fa-phone"></i> Contato</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contato">Contato *</label>
                                <input type="text" class="form-control" id="contato" value="<?php echo htmlspecialchars($paciente['contato']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contato_emergencia">Contato de Emergência</label>
                                <input type="text" class="form-control" id="contato_emergencia" value="<?php echo htmlspecialchars($paciente['contato_emergencia']); ?>">
                            </div>
                        </div>
                        
                        <!-- Informações Profissionais -->
                        <div class="col-md-12 mt-3">
                            <h5 class="text-primary"><i class="fas fa-briefcase"></i> Informações Profissionais</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="escolaridade">Escolaridade</label>
                                <select class="form-control" id="escolaridade">
                                    <option value="">Selecione...</option>
                                    <option value="Fundamental Incompleto" <?php echo $paciente['escolaridade'] == 'Fundamental Incompleto' ? 'selected' : ''; ?>>Fundamental Incompleto</option>
                                    <option value="Fundamental Completo" <?php echo $paciente['escolaridade'] == 'Fundamental Completo' ? 'selected' : ''; ?>>Fundamental Completo</option>
                                    <option value="Médio Incompleto" <?php echo $paciente['escolaridade'] == 'Médio Incompleto' ? 'selected' : ''; ?>>Médio Incompleto</option>
                                    <option value="Médio Completo" <?php echo $paciente['escolaridade'] == 'Médio Completo' ? 'selected' : ''; ?>>Médio Completo</option>
                                    <option value="Superior Incompleto" <?php echo $paciente['escolaridade'] == 'Superior Incompleto' ? 'selected' : ''; ?>>Superior Incompleto</option>
                                    <option value="Superior Completo" <?php echo $paciente['escolaridade'] == 'Superior Completo' ? 'selected' : ''; ?>>Superior Completo</option>
                                    <option value="Pós-graduação" <?php echo $paciente['escolaridade'] == 'Pós-graduação' ? 'selected' : ''; ?>>Pós-graduação</option>
                                    <option value="Mestrado" <?php echo $paciente['escolaridade'] == 'Mestrado' ? 'selected' : ''; ?>>Mestrado</option>
                                    <option value="Doutorado" <?php echo $paciente['escolaridade'] == 'Doutorado' ? 'selected' : ''; ?>>Doutorado</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="trabalha">Trabalha?</label>
                                <select class="form-control" id="trabalha">
                                    <option value="Não" <?php echo $paciente['trabalha'] == 'Não' ? 'selected' : ''; ?>>Não</option>
                                    <option value="Sim" <?php echo $paciente['trabalha'] == 'Sim' ? 'selected' : ''; ?>>Sim</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="onde_trabalha">Onde Trabalha</label>
                                <input type="text" class="form-control" id="onde_trabalha" value="<?php echo htmlspecialchars($paciente['onde_trabalha']); ?>">
                            </div>
                        </div>
                        
                        <!-- Observações -->
                        <div class="col-md-12 mt-3">
                            <h5 class="text-primary"><i class="fas fa-sticky-note"></i> Observações</h5>
                            <hr>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="obs">Observações</label>
                                <textarea class="form-control" id="obs" rows="4"><?php echo htmlspecialchars($paciente['obs']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='ver_paciente.php?id=<?php echo $paciente['id']; ?>'">
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
    
    <script src="editar_paciente.js"></script>
</body>

</html>