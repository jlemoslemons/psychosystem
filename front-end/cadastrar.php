<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Cadastro - PsychoSystem</title>
</head>

<body>
    <div id="containerCadastro" name="containerCadastro">
        <h1>Cadastro</h1>

        <label for="nome">Nome</label>
        <input id="nome" type="text" placeholder="Digite seu nome" class="form-control"><br>

        <label for="usuario">Usuário</label>
        <input id="usuario" type="text" placeholder="Digite seu usuário" class="form-control"><br>

        <label for="senha">Senha</label>
        <input id="senha" type="password" placeholder="Digite sua senha" class="form-control"><br>

        <label for="confirmarSenha">Confirmar Senha</label>
        <input id="confirmarSenha" type="password" placeholder="Confirme sua senha" class="form-control"><br>

        <button id="cadastrar" type="submit" class="btn btn-primary">Cadastrar</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='../index.php'">Voltar</button>
    </div>
    <script src="cadastrar.js"></script>
</body>

</html>