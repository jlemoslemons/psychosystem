<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>PsychoSystem</title>
    <style>
        #container {
            max-width: 450px;
            margin: 80px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #container h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 600;
        }

        @media screen and (max-width: 768px) {
            #container {
                margin: 40px 20px;
                padding: 30px 20px;
            }
            
            #container h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div id="container" name="container">
        <h1>PsychoSystem</h1>
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