<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Cadastro</title>
</head>

<body>
    <div id="containerCadastro" name="containerCadastro">
        <h1>Cadastro</h1>
        <input id="nome" placeholder="Digite seu nome.">Nome</input><br>
        <input id="usuario" placeholder="Digite seu usuário.">Usuário</input><br>
        <input id="senha" type="password_hash" placeholder="Digite sua senha.">Senha</input><br>
        <input id="confirmarSenha" type="password_hash" placeholder="Confirme sua senha">Confirmar Senha</input><br>
        <button id="cadastrar" type="submit">Cadastrar</button>
    </div>
    <script src="./back-end/cadastrar.js"></script>
</body>

</html>