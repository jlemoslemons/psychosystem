<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Pegar informações do usuário da sessão
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_usuario = $_SESSION['usuario_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Visão Geral - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-5" style="padding-left: 80px;">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2>Bem-vindo ao PsychoSystem</h2>
                    </div>
                    <div class="card-body">
                        <h4>Olá, <?php echo htmlspecialchars($usuario_nome); ?>!</h4>
                        <p class="text-muted">Usuário: <?php echo htmlspecialchars($usuario_usuario); ?></p>

                        <hr>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card text-center" style="cursor: pointer;" onclick="window.location.href='cadastrar_paciente.php'">
                                    <div class="card-body">
                                        <i class="fas fa-user-plus fa-3x mb-3 text-primary"></i>
                                        <h5>Novo Paciente</h5>
                                        <p class="text-muted">Cadastrar novo paciente</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center" style="cursor: pointer;" onclick="window.location.href='listar_pacientes.php'">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-3x mb-3 text-success"></i>
                                        <h5>Pacientes</h5>
                                        <p class="text-muted">Gerenciar pacientes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fas fa-file-medical-alt fa-3x mb-3 text-info"></i>
                                        <h5>Prontuários</h5>
                                        <p class="text-muted">Em breve</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>