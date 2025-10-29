<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>PsychoSystem</title>
</head>

<body>
    <div id="container" name="container">
        <h1>PsicoSystem</h1>
        <label for="usuario">Usuário</label>
        <input id="usuario" type="text" placeholder="Digite seu usuário" class="form-control"><br>
        
        <label for="senha">Senha</label>
        <input id="senha" type="password" placeholder="Digite sua senha" class="form-control"><br>
        
        <button id="login" type="submit" class="btn btn-primary">Login</button>
        <button id="cadastrar" type="button" class="btn btn-secondary" onclick="window.location.href='front-end/cadastrar.php'">Cadastrar</button>
    </div>
    <script src="index.js"></script>
</body>

</html>