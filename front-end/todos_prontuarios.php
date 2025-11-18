<?php
session_start();
require_once '../back-end/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

// Buscar todos os prontuários do usuário com informações do paciente
try {
    $stmt = $pdo->prepare("
        SELECT p.*, pac.nome as paciente_nome, pac.id as paciente_id
        FROM prontuarios p
        INNER JOIN pacientes pac ON p.paciente_id = pac.id
        WHERE p.usuario_id = ?
        ORDER BY p.data_sessao DESC, p.numero_sessao DESC
    ");
    $stmt->execute([$usuario_id]);
    $prontuarios = $stmt->fetchAll();
    
    // Contar total de sessões
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM prontuarios WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
    $total_sessoes = $stmt->fetch()['total'];
    
    // Contar total de pacientes com prontuários
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT paciente_id) as total FROM prontuarios WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
    $total_pacientes = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    $erro = "Erro ao buscar prontuários: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Prontuários - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-medical-alt fa-3x mb-3 text-info"></i>
                        <h3><?php echo $total_sessoes; ?></h3>
                        <p class="text-muted">Total de Sessões</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x mb-3 text-success"></i>
                        <h3><?php echo $total_pacientes; ?></h3>
                        <p class="text-muted">Pacientes com Prontuários</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-check fa-3x mb-3 text-primary"></i>
                        <h3><?php echo date('d/m/Y'); ?></h3>
                        <p class="text-muted">Data de Hoje</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabela de Prontuários -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-medical-alt"></i> Todos os Prontuários</h3>
                <div>
                    <button class="btn btn-success" onclick="window.location.href='listar_pacientes.php'">
                        <i class="fas fa-plus"></i> Novo Prontuário
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php elseif (empty($prontuarios)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Nenhum prontuário cadastrado ainda.
                        <br><br>
                        <button class="btn btn-primary" onclick="window.location.href='listar_pacientes.php'">
                            <i class="fas fa-user-plus"></i> Selecionar Paciente para Criar Prontuário
                        </button>
                    </div>
                <?php else: ?>
                    <!-- Filtro por paciente -->
                    <div class="mb-3">
                        <label for="filtroPaciente">Filtrar por Paciente:</label>
                        <select class="form-control" id="filtroPaciente" style="max-width: 400px;">
                            <option value="">Todos os pacientes</option>
                            <?php
                            $stmt = $pdo->prepare("
                                SELECT DISTINCT pac.id, pac.nome 
                                FROM pacientes pac
                                INNER JOIN prontuarios p ON pac.id = p.paciente_id
                                WHERE p.usuario_id = ?
                                ORDER BY pac.nome
                            ");
                            $stmt->execute([$usuario_id]);
                            $pacientes_com_pront = $stmt->fetchAll();
                            
                            foreach ($pacientes_com_pront as $pac) {
                                echo '<option value="' . $pac['id'] . '">' . htmlspecialchars($pac['nome']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabelaProntuarios">
                            <thead class="thead-light">
                                <tr>
                                    <th>Paciente</th>
                                    <th>Sessão</th>
                                    <th>Data</th>
                                    <th>Compareceu</th>
                                    <th>Modalidade</th>
                                    <th>Horário</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prontuarios as $prontuario): ?>
                                <tr data-paciente-id="<?php echo $prontuario['paciente_id']; ?>">
                                    <td>
                                        <strong><?php echo htmlspecialchars($prontuario['paciente_nome']); ?></strong>
                                    </td>
                                    <td>Sessão #<?php echo $prontuario['numero_sessao']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($prontuario['data_sessao'])); ?></td>
                                    <td>
                                        <?php if ($prontuario['compareceu'] === 'Sim'): ?>
                                            <span class="badge badge-success">Sim</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Não</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($prontuario['modalidade'] ?: '-'); ?></td>
                                    <td>
                                        <?php 
                                        if ($prontuario['hora_inicio'] && $prontuario['hora_termino']) {
                                            echo date('H:i', strtotime($prontuario['hora_inicio'])) . ' - ' . date('H:i', strtotime($prontuario['hora_termino']));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" onclick="window.location.href='ver_prontuario.php?id=<?php echo $prontuario['id']; ?>'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="window.location.href='editar_prontuario.php?id=<?php echo $prontuario['id']; ?>'">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="excluirProntuario(<?php echo $prontuario['id']; ?>, <?php echo $prontuario['numero_sessao']; ?>, '<?php echo htmlspecialchars($prontuario['paciente_nome']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Total de prontuários: <strong id="totalProntuarios"><?php echo count($prontuarios); ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Filtro de pacientes
        document.getElementById('filtroPaciente')?.addEventListener('change', function() {
            const pacienteId = this.value;
            const linhas = document.querySelectorAll('#tabelaProntuarios tbody tr');
            let contador = 0;
            
            linhas.forEach(linha => {
                if (pacienteId === '' || linha.dataset.pacienteId === pacienteId) {
                    linha.style.display = '';
                    contador++;
                } else {
                    linha.style.display = 'none';
                }
            });
            
            document.getElementById('totalProntuarios').textContent = contador;
        });
        
        // Excluir prontuário
        async function excluirProntuario(id, numeroSessao, nomePaciente) {
            if (confirm('Tem certeza que deseja excluir o prontuário da Sessão #' + numeroSessao + ' de ' + nomePaciente + '?\n\nEsta ação não pode ser desfeita!')) {
                try {
                    const response = await fetch('../back-end/excluir_prontuario.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    });
                    
                    const resultado = await response.json();
                    
                    if (resultado.sucesso) {
                        alert(resultado.mensagem);
                        location.reload();
                    } else {
                        alert('Erro: ' + resultado.mensagem);
                    }
                    
                } catch (error) {
                    alert('Erro ao excluir prontuário: ' + error.message);
                }
            }
        }
    </script>
</body>

</html>