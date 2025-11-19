document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarProntuario');
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
        
        // Validação: se não compareceu, justificativa é obrigatória
        if (selectCompareceu.value === 'Não' && !inputJustificativa.value.trim()) {
            alert('Por favor, preencha a justificativa de ausência!');
            inputJustificativa.focus();
            return;
        }
        
        const dados = {
            id: document.getElementById('prontuario_id').value,
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
        
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        
        try {
            const response = await fetch('../back-end/atualizar_prontuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dados)
            });
            
            const resultado = await response.json();
            
            if (resultado.sucesso) {
                alert(resultado.mensagem);
                window.location.href = 'ver_prontuario.php?id=' + dados.id;
            } else {
                alert('Erro: ' + resultado.mensagem);
            }
            
        } catch (error) {
            alert('Erro ao salvar alterações: ' + error.message);
        } finally {
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
        }
    });
});