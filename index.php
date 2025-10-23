<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="front-end/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>PsicoSystem</title>
</head>

<body>
    <div id="container" name="container">
        <h1>PsicoSystem</h1>
        <input id="usuario" placeholder="Digite seu usuário.">Usuário</input><br>
        <input id="senha" type="password_hash" placeholder="Digite sua senha.">Senha</input><br>
        <button id="login" type="submit">Login</button>
        <button id="cadastrar" type="button" onclick="window.location.href='front-end/cadastrar.php'">Cadastrar</button>
    </div>
    <script src="back-end/index.js"></script>
</body>

</html>