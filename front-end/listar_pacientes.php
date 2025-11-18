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

// Buscar pacientes do usuário logado
try {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE usuario_id = ? ORDER BY nome ASC");
    $stmt->execute([$usuario_id]);
    $pacientes = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro = "Erro ao buscar pacientes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Pacientes - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>
    
    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-users"></i> Meus Pacientes</h3>
                <div>
                    <button class="btn btn-success" onclick="window.location.href='cadastrar_paciente.php'">
                        <i class="fas fa-user-plus"></i> Novo Paciente
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php elseif (empty($pacientes)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Nenhum paciente cadastrado ainda.
                        <br><br>
                        <button class="btn btn-primary" onclick="window.location.href='cadastrar_paciente.php'">
                            <i class="fas fa-user-plus"></i> Cadastrar Primeiro Paciente
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Idade</th>
                                    <th>CPF</th>
                                    <th>Contato</th>
                                    <th>Cidade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pacientes as $paciente): ?>
                                <tr>
                                    <td><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($paciente['nome']); ?></td>
                                    <td><?php echo $paciente['idade']; ?> anos</td>
                                    <td><?php echo htmlspecialchars($paciente['cpf']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['contato']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['cidade']); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" onclick="verDetalhes(<?php echo $paciente['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="novoProntuario(<?php echo $paciente['id']; ?>)" title="Novo Prontuário">
                                            <i class="fas fa-file-medical"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editar(<?php echo $paciente['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="excluir(<?php echo $paciente['id']; ?>, '<?php echo htmlspecialchars($paciente['nome']); ?>')">
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
                            <i class="fas fa-info-circle"></i> Total de pacientes: <strong><?php echo count($pacientes); ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function verDetalhes(id) {
            window.location.href = 'ver_paciente.php?id=' + id;
        }
        
        function novoProntuario(id) {
            window.location.href = 'prontuarios.php?paciente_id=' + id;
        }
        
        function editar(id) {
            window.location.href = 'editar_paciente.php?id=' + id;
        }
        
        async function excluir(id, nome) {
            if (confirm('Tem certeza que deseja excluir o paciente ' + nome + '?\n\nEsta ação não pode ser desfeita!')) {
                try {
                    const response = await fetch('../back-end/excluir_paciente.php', {
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
                    alert('Erro ao excluir paciente: ' + error.message);
                }
            }
        }
    </script>
</body>

</html>