<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Cadastro - PsychoSystem</title>
    <style>
        #containerCadastro {
            max-width: 450px;
            margin: 60px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #containerCadastro h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 600;
        }

        @media screen and (max-width: 768px) {
            #containerCadastro {
                margin: 40px 20px;
                padding: 30px 20px;
            }
            
            #containerCadastro h1 {
                font-size: 1.8rem;
            }
        }
    </style>
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