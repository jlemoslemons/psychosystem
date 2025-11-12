document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarPaciente');
    const btnSalvar = document.getElementById('btnSalvar');
    
    // Calcular idade automaticamente
    document.getElementById('data_nascimento').addEventListener('change', function() {
        const dataNasc = new Date(this.value);
        const hoje = new Date();
        let idade = hoje.getFullYear() - dataNasc.getFullYear();
        const mes = hoje.getMonth() - dataNasc.getMonth();
        
        if (mes < 0 || (mes === 0 && hoje.getDate() < dataNasc.getDate())) {
            idade--;
        }
        
        document.getElementById('idade').value = idade >= 0 ? idade : 0;
    });
    
    // Máscaras de input
    document.getElementById('cpf').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        }
    });
    
    document.getElementById('cep').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        }
    });
    
    const mascaraTelefone = function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        }
    };
    
    document.getElementById('contato').addEventListener('input', mascaraTelefone);
    document.getElementById('contato_emergencia').addEventListener('input', mascaraTelefone);
    
    // Buscar CEP
    document.getElementById('cep').addEventListener('blur', async function() {
        const cep = this.value.replace(/\D/g, '');
        
        if (cep.length === 8) {
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (!data.erro) {
                    document.getElementById('endereco').value = `${data.logradouro}, ${data.bairro}`;
                    document.getElementById('cidade').value = `${data.localidade} - ${data.uf}`;
                }
            } catch (error) {
                console.log('Erro ao buscar CEP:', error);
            }
        }
    });
    
    // Enviar formulário
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const dados = {
            id: document.getElementById('paciente_id').value,
            nome: document.getElementById('nome').value,
            data_nascimento: document.getElementById('data_nascimento').value,
            idade: document.getElementById('idade').value,
            cpf: document.getElementById('cpf').value,
            rg: document.getElementById('rg').value,
            naturalidade: document.getElementById('naturalidade').value,
            sexo: document.getElementById('sexo').value,
            endereco: document.getElementById('endereco').value,
            cep: document.getElementById('cep').value,
            cidade: document.getElementById('cidade').value,
            contato: document.getElementById('contato').value,
            contato_emergencia: document.getElementById('contato_emergencia').value,
            escolaridade: document.getElementById('escolaridade').value,
            trabalha: document.getElementById('trabalha').value,
            onde_trabalha: document.getElementById('onde_trabalha').value,
            obs: document.getElementById('obs').value
        };
        
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        
        try {
            const response = await fetch('../back-end/editar_paciente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dados)
            });
            
            const resultado = await response.json();
            
            if (resultado.sucesso) {
                alert(resultado.mensagem);
                window.location.href = 'ver_paciente.php?id=' + dados.id;
            } else {
                alert(resultado.mensagem);
            }
            
        } catch (error) {
            alert('Erro ao salvar alterações: ' + error.message);
        } finally {
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
        }
    });
});