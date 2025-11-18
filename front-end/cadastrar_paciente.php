<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$usuario_nome = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Cadastrar Paciente - PsychoSystem</title>
</head>

<body>
    <?php include 'dashboard.php'; ?>

    <div class="container mt-4" style="padding-left: 80px;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-user-plus"></i> Cadastrar Paciente</h3>
            </div>
            <div class="card-body">
                <form id="formPaciente">
                    <div class="row">
                        <!-- Dados Pessoais -->
                        <div class="col-md-12">
                            <h5 class="text-primary"><i class="fas fa-user"></i> Dados Pessoais</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="data_nascimento">Data de Nascimento *</label>
                                <input type="date" class="form-control" id="data_nascimento" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="idade">Idade *</label>
                                <input type="number" class="form-control" id="idade" min="0" max="150" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cpf">CPF *</label>
                                <input type="text" class="form-control" id="cpf" placeholder="000.000.000-00" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rg">RG</label>
                                <input type="text" class="form-control" id="rg">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="naturalidade">Naturalidade</label>
                                <input type="text" class="form-control" id="naturalidade">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sexo">Sexo *</label>
                                <select class="form-control" id="sexo" required>
                                    <option value="">Selecione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Outro">Outro</option>
                                    <option value="Prefiro não informar">Prefiro não informar</option>
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
                                <input type="text" class="form-control" id="cep" placeholder="00000-000">
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <input type="text" class="form-control" id="endereco" placeholder="Rua, número, bairro">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" id="cidade">
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
                                <input type="text" class="form-control" id="contato" placeholder="(00) 00000-0000" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contato_emergencia">Contato de Emergência</label>
                                <input type="text" class="form-control" id="contato_emergencia" placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <!-- Informações Profissionais -->
                        <div class="col-md-12 mt-3">
                            <h5 class="text-primary"><i class="fas fa-briefcase"></i> Informações Profissionais e Acadêmicas</h5>
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="escolaridade">Escolaridade</label>
                                <select class="form-control" id="escolaridade">
                                    <option value="">Selecione...</option>
                                    <option value="Fundamental Incompleto">Fundamental Incompleto</option>
                                    <option value="Fundamental Completo">Fundamental Completo</option>
                                    <option value="Médio Incompleto">Médio Incompleto</option>
                                    <option value="Médio Completo">Médio Completo</option>
                                    <option value="Superior Incompleto">Superior Incompleto</option>
                                    <option value="Superior Completo">Superior Completo</option>
                                    <option value="Pós-graduação">Pós-graduação</option>
                                    <option value="Mestrado">Mestrado</option>
                                    <option value="Doutorado">Doutorado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="trabalha">Trabalha?</label>
                                <select class="form-control" id="trabalha">
                                    <option value="Não">Não</option>
                                    <option value="Sim">Sim</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="onde_trabalha">Onde Trabalha</label>
                                <input type="text" class="form-control" id="onde_trabalha">
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
                                <textarea class="form-control" id="obs" rows="4" placeholder="Informações adicionais sobre o paciente..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='visao_geral.php'">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCadastrar">
                            <i class="fas fa-save"></i> Cadastrar Paciente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Verificar se o JavaScript está carregando -->
    <script>
        console.log('=== INÍCIO DO CARREGAMENTO ===');
        console.log('Página: cadastrar_paciente.php');
        console.log('Formulário existe?', document.getElementById('formPaciente') ? 'SIM' : 'NÃO');
        console.log('Botão existe?', document.getElementById('btnCadastrar') ? 'SIM' : 'NÃO');
    </script>

    <script src="cadastrar_paciente.js"></script>

    <script>
        console.log('=== APÓS CARREGAR JS EXTERNO ===');
        console.log('Tudo carregado!');
    </script>
</body>

</html>