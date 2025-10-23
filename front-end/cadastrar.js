document.addEventListener('DOMContentLoaded', function() {
    const btnCadastrar = document.getElementById('cadastrar');
    
    btnCadastrar.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const nome = document.getElementById('nome').value;
        const usuario = document.getElementById('usuario').value;
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmarSenha').value;
        
        // Validações básicas no frontend
        if (!nome || !usuario || !senha || !confirmarSenha) {
            alert('Todos os campos são obrigatórios!');
            return;
        }
        
        if (senha !== confirmarSenha) {
            alert('As senhas não coincidem!');
            return;
        }
        
        if (senha.length < 6) {
            alert('A senha deve ter no mínimo 6 caracteres!');
            return;
        }
        
        // Desabilitar botão durante o envio
        btnCadastrar.disabled = true;
        btnCadastrar.textContent = 'Cadastrando...';
        
        try {
            const response = await fetch('../back-end/processar_cadastro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nome: nome,
                    usuario: usuario,
                    senha: senha,
                    confirmarSenha: confirmarSenha
                })
            });
            
            const resultado = await response.json();
            
            if (resultado.sucesso) {
                alert(resultado.mensagem);
                // Redirecionar para página de login
                window.location.href = '../index.php';
            } else {
                alert(resultado.mensagem);
            }
            
        } catch (error) {
            alert('Erro ao processar cadastro: ' + error.message);
        } finally {
            btnCadastrar.disabled = false;
            btnCadastrar.textContent = 'Cadastrar';
        }
    });
});