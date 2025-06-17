document.addEventListener('DOMContentLoaded', function() {
    // Abrir modal para criar departamento
    document.getElementById('btn-criar-departamento').addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Novo Departamento';
        document.getElementById('departamento-id').value = '';
        document.getElementById('departamento-form').reset();
        document.getElementById('departamento-modal').classList.remove('hidden');
    });

    // Fechar modal
    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('departamento-modal').classList.add('hidden');
    });

    // Fechar modal de visualização
    document.getElementById('close-view-modal').addEventListener('click', function() {
        document.getElementById('view-modal').classList.add('hidden');
    });

    // Fechar modal de confirmação
    document.getElementById('cancel-delete').addEventListener('click', function() {
        document.getElementById('confirm-modal').classList.add('hidden');
    });

    // Salvar departamento (criar ou atualizar)
    document.getElementById('save-departamento').addEventListener('click', function() {
        const departamentoId = document.getElementById('departamento-id').value;
        const nome = document.getElementById('nome').value;
        const localizacao = document.getElementById('localizacao').value;
        const url = departamentoId ? `/departamentos/${departamentoId}` : '/departamentos';
        const method = departamentoId ? 'PUT' : 'POST';
        
        showLoading();
        
        axios({
            method: method,
            url: url,
            data: {
                nome: nome,
                localizacao: localizacao,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(function(response) {
            document.getElementById('departamento-modal').classList.add('hidden');
            showAlert('Departamento salvo com sucesso!', 'success');
            loadDepartamentos();
        })
        .catch(function(error) {
            console.error('Erro ao salvar departamento:', error);
            showAlert('Erro ao salvar departamento. Por favor, tente novamente.', 'error');
        })
        .finally(function() {
            hideLoading();
        });
    });

    // Carregar ações para os botões de edição
    document.querySelectorAll('.edit-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showLoading();
            
            axios.get(`/departamentos/${id}`)
                .then(function(response) {
                    const departamento = response.data;
                    document.getElementById('modal-title').textContent = 'Editar Departamento';
                    document.getElementById('departamento-id').value = departamento.id;
                    document.getElementById('nome').value = departamento.nome;
                    document.getElementById('localizacao').value = departamento.regiao.nome;
                    document.getElementById('departamento-modal').classList.remove('hidden');
                })
                .catch(function(error) {
                    console.error('Erro ao carregar departamento:', error);
                    showAlert('Erro ao carregar dados do departamento.', 'error');
                })
                .finally(function() {
                    hideLoading();
                });
        });
    });

    // Visualizar departamento
    document.querySelectorAll('.view-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showLoading();
            
            axios.get(`/departamentos/${id}`)
                .then(function(response) {
                    const departamento = response.data;
                    document.getElementById('view-id').textContent = departamento.id;
                    document.getElementById('view-nome').textContent = departamento.nome;
                    document.getElementById('view-localizacao').textContent = departamento.localizacao;
                    document.getElementById('view-modal').classList.remove('hidden');
                })
                .catch(function(error) {
                    console.error('Erro ao carregar detalhes do departamento:', error);
                    showAlert('Erro ao carregar detalhes do departamento.', 'error');
                })
                .finally(function() {
                    hideLoading();
                });
        });
    });

    // Confirmar exclusão
    document.querySelectorAll('.delete-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('delete-id').value = id;
            document.getElementById('confirm-modal').classList.remove('hidden');
        });
    });

    // Excluir departamento
    document.getElementById('confirm-delete').addEventListener('click', function() {
        const id = document.getElementById('delete-id').value;
        showLoading();
        
        axios.delete(`/departamentos/${id}`, {
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(function(response) {
            document.getElementById('confirm-modal').classList.add('hidden');
            showAlert('Departamento excluído com sucesso!', 'success');
            loadDepartamentos();
        })
        .catch(function(error) {
            console.error('Erro ao excluir departamento:', error);
            showAlert('Erro ao excluir departamento.', 'error');
        })
        .finally(function() {
            hideLoading();
        });
    });

    // Função para carregar departamentos
    function loadDepartamentos() {
        showLoading();
        
        axios.get('/departamentos')
            .then(function(response) {
                // Assumindo que o endpoint retorna um objeto com propriedade 'departamentos'
                const departamentos = response.data.departamentos || [];
                const tableBody = document.getElementById('departamentos-table-body');
                
                tableBody.innerHTML = '';
                
                departamentos.forEach(departamento => {
                    tableBody.innerHTML += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${departamento.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${departamento.nome}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${departamento.localizacao}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3 view-departamento" data-id="${departamento.id}">Visualizar</button>
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 edit-departamento" data-id="${departamento.id}">Editar</button>
                                <button type="button" class="text-red-600 hover:text-red-900 delete-departamento" data-id="${departamento.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                });
                
                // Recarregar event listeners para os botões
                attachEventListeners();
            })
            .catch(function(error) {
                console.error('Erro ao carregar departamentos:', error);
                showAlert('Erro ao carregar lista de departamentos.', 'error');
            })
            .finally(function() {
                hideLoading();
            });
    }

    // Função para anexar event listeners aos botões após recarregar a tabela
    function attachEventListeners() {
        document.querySelectorAll('.view-departamento').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                showLoading();
                
                axios.get(`/departamentos/${id}`)
                    .then(function(response) {
                        const departamento = response.data;
                        document.getElementById('view-id').textContent = departamento.id;
                        document.getElementById('view-nome').textContent = departamento.nome;
                        document.getElementById('view-localizacao').textContent = departamento.localizacao;
                        document.getElementById('view-modal').classList.remove('hidden');
                    })
                    .catch(function(error) {
                        console.error('Erro ao carregar detalhes do departamento:', error);
                        showAlert('Erro ao carregar detalhes do departamento.', 'error');
                    })
                    .finally(function() {
                        hideLoading();
                    });
            });
        });
        
        document.querySelectorAll('.edit-departamento').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                showLoading();
                
                axios.get(`/departamentos/${id}/edit`)
                    .then(function(response) {
                        const departamento = response.data;
                        document.getElementById('modal-title').textContent = 'Editar Departamento';
                        document.getElementById('departamento-id').value = departamento.id;
                        document.getElementById('nome').value = departamento.nome;
                        document.getElementById('localizacao').value = departamento.localizacao;
                        document.getElementById('departamento-modal').classList.remove('hidden');
                    })
                    .catch(function(error) {
                        console.error('Erro ao carregar departamento:', error);
                        showAlert('Erro ao carregar dados do departamento.', 'error');
                    })
                    .finally(function() {
                        hideLoading();
                    });
            });
        });
        
        document.querySelectorAll('.delete-departamento').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete-id').value = id;
                document.getElementById('confirm-modal').classList.remove('hidden');
            });
        });
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
            alertContent.innerHTML = `
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            `;
        } else {
            alertContent.className = 'px-4 py-3 rounded relative bg-red-100 border border-red-400 text-red-700';
            alertContent.innerHTML = `
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            `;
        }
        
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