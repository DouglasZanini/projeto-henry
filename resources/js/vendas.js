document.addEventListener('DOMContentLoaded', function() {
    // Função para atualizar preço do produto com base na seleção
    const updateProdutoPreco = (select) => {
        const option = select.options[select.selectedIndex];
        const precoInput = select.closest('.produto-item').querySelector('input[name$="[preco]"]');
        
        if (option && option.dataset.preco) {
            precoInput.value = parseFloat(option.dataset.preco).toFixed(2);
            // Atualizar o valor total após atualizar o preço unitário
            updateTotal(precoInput.closest('.produto-item'));
        } else {
            precoInput.value = '';
            // Zerar o total se não houver preço
            const totalInput = precoInput.closest('.produto-item').querySelector('.preco-total');
            if (totalInput) totalInput.textContent = 'R$ 0,00';
        }
    };
    
    // Função para calcular o valor total do item
    const updateTotal = (produtoItem) => {
        const quantidade = parseInt(produtoItem.querySelector('input[name$="[quantidade]"]').value) || 0;
        const precoUnitario = parseFloat(produtoItem.querySelector('input[name$="[preco]"]').value) || 0;
        const total = quantidade * precoUnitario;
        
        // Atualizar o texto do total
        const totalElement = produtoItem.querySelector('.preco-total');
        if (totalElement) {
            totalElement.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
        
        // Recalcular o total geral sempre que um item individual for atualizado
        calcularTotalGeral();
    };
    
    // Configurar event listeners para selects de produto existentes
    document.querySelectorAll('.produto-select').forEach(select => {
        select.addEventListener('change', () => updateProdutoPreco(select));
    });
    
    // Configurar event listeners para campos de quantidade e preço
    document.querySelectorAll('input[name$="[quantidade]"]').forEach(input => {
        input.addEventListener('change', () => updateTotal(input.closest('.produto-item')));
        input.addEventListener('keyup', () => updateTotal(input.closest('.produto-item')));
    });
    
    document.querySelectorAll('input[name$="[preco]"]').forEach(input => {
        input.addEventListener('change', () => updateTotal(input.closest('.produto-item')));
        input.addEventListener('keyup', () => updateTotal(input.closest('.produto-item')));
    });
    
    // Contador para IDs únicos dos novos produtos
    let produtoCount = 1;
    
    // Adicionar novo produto
    document.getElementById('add-produto').addEventListener('click', function() {
        const container = document.getElementById('produtos-container');
        const template = container.querySelector('.produto-item').cloneNode(true);
        
        // Atualizar índices e limpar valores
        const selects = template.querySelectorAll('select');
        const inputs = template.querySelectorAll('input');
        
        selects.forEach(select => {
            select.name = select.name.replace(/\[\d+\]/, `[${produtoCount}]`);
            select.selectedIndex = 0;
            select.addEventListener('change', () => updateProdutoPreco(select));
        });
        
        inputs.forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${produtoCount}]`);
            input.value = '';
            
            // Adicionar eventos para quantidade e preço
            if (input.name.includes('[quantidade]') || input.name.includes('[preco]')) {
                input.addEventListener('change', () => updateTotal(input.closest('.produto-item')));
                input.addEventListener('keyup', () => updateTotal(input.closest('.produto-item')));
            }
        });
        
        // Resetar o valor total
        const totalElement = template.querySelector('.preco-total');
        if (totalElement) {
            totalElement.textContent = 'R$ 0,00';
        }
        
        // Adicionar evento para remover produto
        template.querySelector('.remove-produto').addEventListener('click', function() {
            this.closest('.produto-item').remove();
            calcularTotalGeral();
        });
        
        container.appendChild(template);
        produtoCount++;
        
        // Recalcular o total geral após adicionar um novo produto
        calcularTotalGeral();
    });
    
    // Configurar eventos de remoção para produtos existentes
    document.querySelectorAll('.remove-produto').forEach(button => {
        button.addEventListener('click', function() {
            // Não remover se for o único produto
            const items = document.querySelectorAll('.produto-item');
            if (items.length > 1) {
                this.closest('.produto-item').remove();
                calcularTotalGeral();
            }
        });
    });
    
    // Calcular total geral de todos os itens
    function calcularTotalGeral() {
        let totalGeral = 0;
        
        document.querySelectorAll('.produto-item').forEach(item => {
            const quantidade = parseInt(item.querySelector('input[name$="[quantidade]"]').value) || 0;
            const precoUnitario = parseFloat(item.querySelector('input[name$="[preco]"]').value) || 0;
            totalGeral += quantidade * precoUnitario;
        });
        
        const totalGeralElement = document.getElementById('total-geral');
        if (totalGeralElement) {
            totalGeralElement.textContent = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
        }
        
        // Atualizar um campo oculto para enviar o valor total
        const totalInputHidden = document.getElementById('valor_total');
        if (totalInputHidden) {
            totalInputHidden.value = totalGeral.toFixed(2);
        }
    }
    
    // Adicionar evento para recalcular o total geral quando algo mudar
    document.querySelectorAll('.produto-item input, .produto-item select').forEach(el => {
        el.addEventListener('change', calcularTotalGeral);
        if (el.tagName === 'INPUT') {
            el.addEventListener('keyup', calcularTotalGeral);
        }
    });
    
    // Inicializar totais
    document.querySelectorAll('.produto-item').forEach(item => {
        updateTotal(item);
    });
    calcularTotalGeral();
    
    // Interceptar o envio do formulário para usar Axios
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Mostrar indicador de carregamento
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processando...
        `;
        
        // Coletar todos os dados do formulário
        const formData = new FormData(form);
        
        // Obter o token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Preparar os dados para envio
        let produtos = [];
        let currentProduto = {};
        let currentIndex = -1;
        
        for (let [key, value] of formData.entries()) {
            if (key.includes('produtos')) {
                // Extrair o índice e o campo (produto_id, quantidade, preco)
                const matches = key.match(/produtos\[(\d+)\]\[([^\]]+)\]/);
                if (matches) {
                    const idx = parseInt(matches[1]);
                    const field = matches[2];
                    
                    if (currentIndex !== idx) {
                        if (currentIndex !== -1) {
                            produtos.push(currentProduto);
                        }
                        currentProduto = {};
                        currentIndex = idx;
                    }
                    
                    currentProduto[field] = value;
                }
            }
        }
        
        // Adicionar o último produto se existir
        if (Object.keys(currentProduto).length > 0) {
            produtos.push(currentProduto);
        }
        
        // Criar o payload final
        const payload = {
            cliente_id: formData.get('cliente_id'),
            vendedor_id: formData.get('vendedor_id'),
            tipo_pagamento: formData.get('tipo_pagamento'),
            produtos: produtos,
            total: document.getElementById('total-geral').textContent.replace('R$', '').trim()
        };
        
        // Enviar os dados via Axios
        axios.post(form.action, payload, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.data.redirect) {
                // Mostrar mensagem de sucesso e redirecionar
                const alertContainer = document.createElement('div');
                alertContainer.className = 'mb-4 p-3 bg-green-200 text-green-800 rounded';
                alertContainer.textContent = response.data.message || 'Venda registrada com sucesso!';
                
                form.parentNode.insertBefore(alertContainer, form);
                
                setTimeout(() => {
                    window.location.href = response.data.redirect;
                }, 1500);
            } else {
                // Recarregar a página atual
                window.location.reload();
            }
        })
        .catch(error => {
            // Restaurar o botão
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            // Tratar erros de validação
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                
                // Limpar erros anteriores
                document.querySelectorAll('.error-message').forEach(el => el.remove());
                
                // Mostrar mensagens de erro
                for (const [field, messages] of Object.entries(errors)) {
                    const inputField = form.querySelector(`[name="${field}"]`);
                    if (inputField) {
                        inputField.classList.add('border-red-500');
                        
                        const errorElement = document.createElement('p');
                        errorElement.className = 'error-message text-red-600 text-sm mt-1';
                        errorElement.textContent = messages[0];
                        
                        inputField.parentNode.appendChild(errorElement);
                    }
                }
            } else {
                // Criar e mostrar mensagem de erro genérica
                const alertContainer = document.createElement('div');
                alertContainer.className = 'mb-4 p-3 bg-red-200 text-red-800 rounded';
                alertContainer.textContent = 'Erro ao registrar venda. Por favor, tente novamente.';
                
                const existingAlert = form.parentNode.querySelector('.bg-red-200');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                form.parentNode.insertBefore(alertContainer, form);
                
                console.error('Erro ao enviar venda:', error);
            }
        });
    });
});