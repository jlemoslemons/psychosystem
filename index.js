document.addEventListener('DOMContentLoaded', function() {
    const btnLogin = document.getElementById('login');
    
    btnLogin.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const usuario = document.getElementById('usuario').value;
        const senha = document.getElementById('senha').value;
        
        // Validações básicas
        if (!usuario || !senha) {
            alert('Usuário e senha são obrigatórios!');
            return;
        }
        
        // Desabilitar botão durante o envio
        btnLogin.disabled = true;
        btnLogin.textContent = 'Entrando...';
        
        try {
            const response = await fetch('back-end/processar_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    usuario: usuario,
                    senha: senha
                })
            });
            
            const resultado = await response.json();
            
            if (resultado.sucesso) {
                alert(resultado.mensagem);
                // Redirecionar para visao_geral.php
                window.location.href = resultado.redirect;
            } else {
                alert(resultado.mensagem);
            }
            
        } catch (error) {
            alert('Erro ao processar login: ' + error.message);
        } finally {
            btnLogin.disabled = false;
            btnLogin.textContent = 'Login';
        }
    });
    
    // Permitir login com Enter
    document.getElementById('senha').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnLogin.click();
        }
    });
});