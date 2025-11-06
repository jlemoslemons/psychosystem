console.log('cadastrar_paciente.js carregado!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado');
    
    const form = document.getElementById('formPaciente');
    const btnCadastrar = document.getElementById('btnCadastrar');
    
    if (!form) {
        console.error('Formulário não encontrado!');
        return;
    }
    
    if (!btnCadastrar) {
        console.error('Botão cadastrar não encontrado!');
        return;
    }
    
    console.log('Elementos encontrados, adicionando listeners');
    
    // Calcular idade automaticamente
    const inputDataNasc = document.getElementById('data_nascimento');
    if (inputDataNasc) {
        inputDataNasc.addEventListener('change', function() {
            const dataNasc = new Date(this.value);
            const hoje = new Date();
            let idade = hoje.getFullYear() - dataNasc.getFullYear();
            const mes = hoje.getMonth() - dataNasc.getMonth();
            
            if (mes < 0 || (mes === 0 && hoje.getDate() < dataNasc.getDate())) {
                idade--;
            }
            
            const inputIdade = document.getElementById('idade');
            if (inputIdade) {
                inputIdade.value = idade >= 0 ? idade : 0;
            }
        });
    }
    
    // Máscaras de input
    const inputCpf = document.getElementById('cpf');
    if (inputCpf) {
        inputCpf.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });
    }
    
    const inputCep = document.getElementById('cep');
    if (inputCep) {
        inputCep.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
        });
        
        // Buscar CEP
        inputCep.addEventListener('blur', async function() {
            const cep = this.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    
                    if (!data.erro) {
                        const inputEndereco = document.getElementById('endereco');
                        const inputCidade = document.getElementById('cidade');
                        
                        if (inputEndereco) {
                            inputEndereco.value = `${data.logradouro}, ${data.bairro}`;
                        }
                        if (inputCidade) {
                            inputCidade.value = `${data.localidade} - ${data.uf}`;
                        }
                    }
                } catch (error) {
                    console.log('Erro ao buscar CEP:', error);
                }
            }
        });
    }
    
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
    
    const inputContato = document.getElementById('contato');
    const inputContatoEmerg = document.getElementById('contato_emergencia');
    
    if (inputContato) {
        inputContato.addEventListener('input', mascaraTelefone);
    }
    if (inputContatoEmerg) {
        inputContatoEmerg.addEventListener('input', mascaraTelefone);
    }
    
    // Enviar formulário
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Formulário submetido!');
        
        // Coletar dados
        const dados = {
            nome: document.getElementById('nome').value,
            data_nascimento: document.getElementById('data_nascimento').value,
            idade: document.getElementById('idade').value,
            cpf: document.getElementById('cpf').value,
            rg: document.getElementById('rg') ? document.getElementById('rg').value : '',
            naturalidade: document.getElementById('naturalidade') ? document.getElementById('naturalidade').value : '',
            sexo: document.getElementById('sexo').value,
            endereco: document.getElementById('endereco') ? document.getElementById('endereco').value : '',
            cep: document.getElementById('cep') ? document.getElementById('cep').value : '',
            cidade: document.getElementById('cidade') ? document.getElementById('cidade').value : '',
            contato: document.getElementById('contato').value,
            contato_emergencia: document.getElementById('contato_emergencia') ? document.getElementById('contato_emergencia').value : '',
            escolaridade: document.getElementById('escolaridade') ? document.getElementById('escolaridade').value : '',
            trabalha: document.getElementById('trabalha') ? document.getElementById('trabalha').value : 'Não',
            onde_trabalha: document.getElementById('onde_trabalha') ? document.getElementById('onde_trabalha').value : '',
            obs: document.getElementById('obs') ? document.getElementById('obs').value : ''
        };
        
        console.log('Dados coletados:', dados);
        
        // Desabilitar botão
        btnCadastrar.disabled = true;
        btnCadastrar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cadastrando...';
        
        try {
            console.log('Enviando requisição...');
            
            const response = await fetch('../back-end/processar_paciente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dados)
            });
            
            console.log('Resposta recebida - Status:', response.status);
            
            const textoResposta = await response.text();
            console.log('Texto da resposta:', textoResposta);
            
            let resultado;
            try {
                resultado = JSON.parse(textoResposta);
                console.log('JSON parseado:', resultado);
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                alert('Erro na resposta do servidor. Veja o console para detalhes.');
                return;
            }
            
            if (resultado.sucesso) {
                console.log('Sucesso! ID:', resultado.paciente_id);
                alert(resultado.mensagem);
                window.location.href = 'listar_pacientes.php';
            } else {
                console.error('Erro:', resultado.mensagem);
                alert('Erro: ' + resultado.mensagem);
            }
            
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert('Erro ao cadastrar paciente: ' + error.message);
        } finally {
            btnCadastrar.disabled = false;
            btnCadastrar.innerHTML = '<i class="fas fa-save"></i> Cadastrar Paciente';
        }
    });
    
    console.log('Event listeners configurados com sucesso!');
});