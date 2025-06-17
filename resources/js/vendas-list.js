document.addEventListener('DOMContentLoaded', function() {
    // Fechar modal de visualização
    document.getElementById('close-view-modal').addEventListener('click', function() {
        document.getElementById('view-modal').classList.add('hidden');
    });

    // Visualizar venda - aqui usamos AJAX como solicitado
    document.querySelectorAll('.view-venda').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showLoading();
            
            axios.get(`/vendas/${id}`)
                .then(function(response) {
                    const venda = response.data;
                    
                    // Preencher dados básicos
                    document.getElementById('view-id').textContent = venda.id;
                    document.getElementById('view-data').textContent = formatDate(venda.data_ordenamento);
                    document.getElementById('view-status').textContent = venda.ordem_cheia === 'Y' ? 'Completa' : 'Em processamento';
                    document.getElementById('view-status').className = venda.ordem_cheia === 'Y' ? 
                        'ml-1 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs' : 
                        'ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs';
                    
                    document.getElementById('view-pagamento').textContent = venda.tipo_pagamento === 'CASH' ? 'Dinheiro' : 'Crédito';
                    document.getElementById('view-cliente').textContent = venda.cliente.nome;
                    document.getElementById('view-vendedor').textContent = `${venda.vendedor.primeiro_nome} ${venda.vendedor.ultimo_nome}`;
                    
                    if (venda.data_expedicao) {
                        document.getElementById('view-expedicao').textContent = formatDate(venda.data_expedicao);
                    } else {
                        document.getElementById('view-expedicao').textContent = 'Pendente';
                    }
                    
                    // Preencher itens
                    const itensContainer = document.getElementById('view-itens');
                    itensContainer.innerHTML = '';
                    
                    venda.itens.forEach(item => {
                        const subtotal = item.preco * item.quantidade;
                        
                        itensContainer.innerHTML += `
                            <tr>
                                <td class="px-4 py-2">${item.item_id}</td>
                                <td class="px-4 py-2">${item.produto.nome}</td>
                                <td class="px-4 py-2 text-right">R$ ${formatCurrency(item.preco)}</td>
                                <td class="px-4 py-2 text-right">${item.quantidade}</td>
                                <td class="px-4 py-2 text-right">${item.quantidade_expedida || 0}</td>
                                <td class="px-4 py-2 text-right">R$ ${formatCurrency(subtotal)}</td>
                            </tr>
                        `;
                    });
                    
                    // Atualizar total
                    document.getElementById('view-total').textContent = `R$ ${formatCurrency(venda.total)}`;
                    
                    document.getElementById('view-modal').classList.remove('hidden');
                })
                .catch(function(error) {
                    console.error('Erro ao carregar detalhes da venda:', error);
                    showAlert('Erro ao carregar detalhes da venda.', 'error');
                })
                .finally(function() {
                    hideLoading();
                });
        });
    });

    // Funções auxiliares
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }
    
    function formatCurrency(value) {
        return parseFloat(value).toFixed(2).replace('.', ',');
    }

    // Funções para mostrar/ocultar loading
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loading').classList.add('hidden');
    }
    
    // Função para mostrar alertas
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alert-container');
        const alertContent = document.getElementById('alert-content');
        
        alertContainer.classList.remove('hidden');
        
        if (type === 'success') {
            alertContent.className = 'px-4 py-3 rounded relative bg-green-100 border border-green-400 text-green-700';
        } else {
            alertContent.className = 'px-4 py-3 rounded relative bg-red-100 border border-red-400 text-red-700';
        }
        
        alertContent.innerHTML = `
            <span class="block sm:inline">${message}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-${type === 'success' ? 'green' : 'red'}-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        `;
        
        // Adiciona um event listener ao botão de fechar
        alertContent.querySelector('svg').addEventListener('click', function() {
            alertContainer.classList.add('hidden');
        });
        
        // Esconde o alerta após 5 segundos
        setTimeout(function() {
            alertContainer.classList.add('hidden');
        }, 5000);
    }
});