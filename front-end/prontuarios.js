console.log('prontuarios.js carregado!');

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formProntuario');
    const btnSalvar = document.getElementById('btnSalvar');
    const selectCompareceu = document.getElementById('compareceu');
    const inputJustificativa = document.getElementById('justificativa_ausencia');
    
    // Habilitar/desabilitar justificativa de ausência
    selectCompareceu.addEventListener('change', function() {
        if (this.value === 'Não') {
            inputJustificativa.disabled = false;
            inputJustificativa.required = true;
        } else {
            inputJustificativa.disabled = true;
            inputJustificativa.required = false;
            inputJustificativa.value = '';
        }
    });
    
    // Enviar formulário
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Formulário submetido!');
        
        // Validação: se não compareceu, justificativa é obrigatória
        if (selectCompareceu.value === 'Não' && !inputJustificativa.value.trim()) {
            alert('Por favor, preencha a justificativa de ausência!');
            inputJustificativa.focus();
            return;
        }
        
        // Coletar dados
        const dados = {
            paciente_id: document.getElementById('paciente_id').value,
            demanda_cliente: document.getElementById('demanda_cliente').value,
            plano_terapeutico: document.getElementById('plano_terapeutico').value,
            numero_sessao: document.getElementById('numero_sessao').value,
            data_sessao: document.getElementById('data_sessao').value,
            compareceu: selectCompareceu.value,
            justificativa_ausencia: inputJustificativa.value,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_termino: document.getElementById('hora_termino').value,
            modalidade: document.getElementById('modalidade').value,
            recursos_tecnicos: document.getElementById('recursos_tecnicos').value,
            dados_relevantes: document.getElementById('dados_relevantes').value
        };
        
        console.log('Dados coletados:', dados);
        
        // Desabilitar botão
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        
        try {
            console.log('Enviando requisição...');
            
            const response = await fetch('../back-end/processar_prontuario.php', {
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
                console.log('Sucesso! ID:', resultado.prontuario_id);
                alert(resultado.mensagem);
                window.location.href = 'listar_prontuarios.php?paciente_id=' + dados.paciente_id;
            } else {
                console.error('Erro:', resultado.mensagem);
                alert('Erro: ' + resultado.mensagem);
            }
            
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert('Erro ao salvar prontuário: ' + error.message);
        } finally {
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Prontuário';
        }
    });
    
    console.log('Event listeners configurados com sucesso!');
});