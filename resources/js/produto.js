document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('produto-modal');
    const form = document.getElementById('produto-form');
    const modalTitle = document.querySelector('#produto-modal h3');
    
    let currentProdutoId = null;

    // Função para abrir modal (criar)
    window.openModal = function() {
        currentProdutoId = null;
        modalTitle.textContent = 'Cadastrar Produto';
        form.reset();
        form.action = '/produtos';
        form.method = 'POST';
        form.querySelector('input[name="_method"]')?.remove();
        modal.classList.remove('hidden');
    }

    // Função para fechar modal
    window.closeModal = function() {
        modal.classList.add('hidden');
    }

    // Submit do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const url = currentProdutoId ? `/produtos/${currentProdutoId}` : '/produtos';
        
        if (currentProdutoId) {
            formData.append('_method', 'PUT');
        }
        
        showLoading(true);
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                closeModal();
                window.location.reload();
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao salvar produto', 'error');
        })
        .finally(() => {
            showLoading(false);
        });
    });

    // Botões de editar
    document.querySelectorAll('.edit-produto').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            currentProdutoId = id;
            
            showLoading(true);
            
            fetch(`/produtos/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    modalTitle.textContent = 'Editar Produto';
                    
                    // Preencher formulário
                    form.querySelector('input[name="nome"]').value = data.nome || '';
                    form.querySelector('input[name="descricao_breve"]').value = data.descricao_breve || '';
                    form.querySelector('input[name="preco_sugerido"]').value = data.preco_sugerido || '';
                    form.querySelector('input[name="unidades"]').value = data.unidades || '';
                    
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erro ao carregar produto:', error);
                    showAlert('Erro ao carregar dados do produto', 'error');
                })
                .finally(() => {
                    showLoading(false);
                });
        });
    });

    // Botões de excluir
    document.querySelectorAll('.delete-produto').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const id = this.getAttribute('data-id');
            
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                showLoading(true);
                
                fetch(`/produtos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        window.location.reload();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro ao excluir produto:', error);
                    showAlert('Erro ao excluir produto', 'error');
                })
                .finally(() => {
                    showLoading(false);
                });
            }
        });
    });

    // Funções utilitárias
    function showLoading(show) {
        let loadingElement = document.getElementById('loading');
        
        if (!loadingElement) {
            loadingElement = document.createElement('div');
            loadingElement.id = 'loading';
            loadingElement.className = 'fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50';
            loadingElement.innerHTML = `
                <div class="bg-white p-4 rounded-full">
                    <svg class="animate-spin h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;
            document.body.appendChild(loadingElement);
        }
        
        if (show) {
            loadingElement.classList.remove('hidden');
        } else {
            loadingElement.classList.add('hidden');
        }
    }

    function showAlert(message, type = 'info') {
        let alertContainer = document.getElementById('alert-container');
        
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.id = 'alert-container';
            alertContainer.className = 'fixed top-4 right-4 z-50 max-w-md';
            document.body.appendChild(alertContainer);
        }

        const alertClass = type === 'success' 
            ? 'bg-green-100 border border-green-400 text-green-700'
            : 'bg-red-100 border border-red-400 text-red-700';

        alertContainer.innerHTML = `
            <div class="px-4 py-3 rounded relative ${alertClass} shadow-lg">
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        `;

        setTimeout(() => {
            if (alertContainer.firstElementChild) {
                alertContainer.firstElementChild.remove();
            }
        }, 5000);
    }
});