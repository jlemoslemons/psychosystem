<?php
session_start();
require_once '../back-end/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$paciente_id = $_GET['paciente_id'] ?? 0;

// Buscar dados do paciente
try {
    $stmt = $pdo->prepare("SELECT id, nome FROM pacientes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$paciente_id, $usuario_id]);
    $paciente = $stmt->fetch();
    
    if (!$paciente) {
        header('Location: listar_pacientes.php');
        exit;
    }
    
    // Buscar prontuários do paciente
    $stmt = $pdo->prepare("SELECT * FROM prontuarios WHERE paciente_id = ? AND usuario_id = ? ORDER BY numero_sessao DESC");
    $stmt->execute([$paciente_id, $usuario_id]);
    $prontuarios = $stmt->fetchAll();
    
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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-medical-alt"></i> Prontuários - <?php echo htmlspecialchars($paciente['nome']); ?></h3>
                <div>
                    <button class="btn btn-success" onclick="window.location.href='prontuarios.php?paciente_id=<?php echo $paciente_id; ?>'">
                        <i class="fas fa-plus"></i> Novo Prontuário
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='ver_paciente.php?id=<?php echo $paciente_id; ?>'">
                        <i class="fas fa-arrow-left"></i> Voltar
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
                        <button class="btn btn-primary" onclick="window.location.href='prontuarios.php?paciente_id=<?php echo $paciente_id; ?>'">
                            <i class="fas fa-plus"></i> Cadastrar Primeiro Prontuário
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
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
                                <tr>
                                    <td><strong>Sessão #<?php echo $prontuario['numero_sessao']; ?></strong></td>
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
                                        <button class="btn btn-sm btn-info" onclick="verProntuario(<?php echo $prontuario['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editarProntuario(<?php echo $prontuario['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="excluirProntuario(<?php echo $prontuario['id']; ?>, <?php echo $prontuario['numero_sessao']; ?>)">
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
                            <i class="fas fa-info-circle"></i> Total de sessões: <strong><?php echo count($prontuarios); ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function verProntuario(id) {
            window.location.href = 'ver_prontuario.php?id=' + id;
        }
        
        function editarProntuario(id) {
            window.location.href = 'editar_prontuario.php?id=' + id;
        }
        
        async function excluirProntuario(id, numeroSessao) {
            if (confirm('Tem certeza que deseja excluir o prontuário da Sessão #' + numeroSessao + '?\n\nEsta ação não pode ser desfeita!')) {
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