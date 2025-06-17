document.addEventListener('DOMContentLoaded', function() {
    // ======= VERIFICAÇÃO DE ELEMENTOS =======
    // Esta seção verifica se os elementos existem antes de adicionar event listeners
    
    // Botão de criar departamento (o botão que abre o primeiro modal com o formulário simples)
    const btnCriar = document.getElementById('btn-criar');
    
    // Modal principal (modal simples)
    const modalPrincipal = document.getElementById('modal');
    
    // Modal de edição/visualização departamento (modal mais complexo)
    const departamentoModal = document.getElementById('departamento-modal');
    const modalClose = document.getElementById('modal-close');
    
    // Form de edição/criação de departamento
    const departamentoForm = document.getElementById('departamento-form');
    const inputNome = document.getElementById('departamento-nome');
    const inputLocalizacao = document.getElementById('departamento-localizacao');
    const inputId = document.getElementById('departamento-id');
    const modalTitle = document.getElementById('modal-title');
    
    // Elementos para exclusão
    const confirmDeleteModal = document.getElementById('confirm-delete-modal');
    const cancelarExclusao = document.getElementById('cancelar-exclusao');
    const confirmarExclusao = document.getElementById('confirmar-exclusao');
    
    // Container de alertas
    const alertContainer = document.getElementById('alert-container');
    
    // ======= HANDLERS DE EVENTOS =======

    // Handler para botões de cancelar em todos os modais
    document.querySelectorAll('.cancelar-modal').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.fixed');
            if (modal) modal.classList.add('hidden');
            
            // Reativar campos e botão no modal de departamento
            if (inputNome) inputNome.removeAttribute('disabled');
            if (inputLocalizacao) inputLocalizacao.removeAttribute('disabled');
            
            // Restaurar visibilidade do botão salvar
            const salvarBtn = departamentoForm?.querySelector('.salvar-modal');
            if (salvarBtn) salvarBtn.style.display = 'block';
        });
    });
    
    // Fechar modal de departamento
    if (modalClose) {
        modalClose.addEventListener('click', function() {
            if (departamentoModal) departamentoModal.classList.add('hidden');
            
            // Reativar campos e botão
            if (inputNome) inputNome.removeAttribute('disabled');
            if (inputLocalizacao) inputLocalizacao.removeAttribute('disabled');
            
            // Restaurar visibilidade do botão salvar
            const salvarBtn = departamentoForm?.querySelector('.salvar-modal');
            if (salvarBtn) salvarBtn.style.display = 'block';
        });
    }
    
    // Fechar modal de confirmação de exclusão
    if (cancelarExclusao) {
        cancelarExclusao.addEventListener('click', function() {
            if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');
        });
    }

    // ======= FUNÇÕES CRUD =======
    
    // Salvar departamento (evento de submit do form no modal de edição)
    if (departamentoForm) {
        const salvarBtn = departamentoForm.querySelector('.salvar-modal');
        if (salvarBtn) {
            salvarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const id = inputId?.value;
                const nome = inputNome?.value;
                const localizacao = inputLocalizacao?.value;
                
                // Validação básica
                if (!nome || !localizacao) {
                    showAlert('Por favor, preencha todos os campos', 'error');
                    return;
                }
                
                const url = id ? `/departamentos/${id}` : '/departamentos';
                const method = id ? 'PUT' : 'POST';
                
                showLoading();
                
                axios({
                    method: method,
                    url: url,
                    data: {
                        nome: nome,
                        localizacao: localizacao,
                        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                })
                .then(function(response) {
                    if (departamentoModal) departamentoModal.classList.add('hidden');
                    showAlert('Departamento salvo com sucesso!', 'success');
                    
                    // Recarregar a página para mostrar a atualização
                    window.location.reload();
                })
                .catch(function(error) {
                    console.error('Erro ao salvar departamento:', error);
                    showAlert('Erro ao salvar departamento. Por favor, tente novamente.', 'error');
                })
                .finally(function() {
                    hideLoading();
                });
            });
        }
    }
    
    // Visualizar departamento (handler para botões de visualização)
    document.querySelectorAll('.view-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id || !departamentoModal || !modalTitle || !inputNome || !inputLocalizacao) {
                return;
            }
            
            showLoading();
            
            axios.get(`/departamentos/${id}`)
                .then(function(response) {
                    const departamento = response.data;
                    
                    // Preencher o formulário em modo de visualização
                    if (modalTitle) modalTitle.textContent = 'Visualizar Departamento';
                    if (inputId) inputId.value = departamento.id;
                    if (inputNome) {
                        inputNome.value = departamento.nome;
                        inputNome.setAttribute('disabled', 'disabled');
                    }
                    if (inputLocalizacao) {
                        inputLocalizacao.value = departamento.localizacao;
                        inputLocalizacao.setAttribute('disabled', 'disabled');
                    }
                    
                    // Ocultar botão de salvar
                    const salvarBtn = departamentoForm?.querySelector('.salvar-modal');
                    if (salvarBtn) salvarBtn.style.display = 'none';
                    
                    // Exibir modal
                    if (departamentoModal) departamentoModal.classList.remove('hidden');
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
    
    // Editar departamento (handler para botões de edição)
    document.querySelectorAll('.edit-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id || !departamentoModal || !modalTitle || !inputNome || !inputLocalizacao) {
                return;
            }
            
            showLoading();
            
            axios.get(`/departamentos/${id}`)
                .then(function(response) {
                    const departamento = response.data;
                    
                    // Preencher o formulário para edição
                    if (modalTitle) modalTitle.textContent = 'Editar Departamento';
                    if (inputId) inputId.value = departamento.id;
                    if (inputNome) {
                        inputNome.value = departamento.nome;
                        inputNome.removeAttribute('disabled');
                    }
                    if (inputLocalizacao) {
                        inputLocalizacao.value = departamento.localizacao;
                        inputLocalizacao.removeAttribute('disabled');
                    }
                    
                    // Mostrar botão de salvar
                    const salvarBtn = departamentoForm?.querySelector('.salvar-modal');
                    if (salvarBtn) salvarBtn.style.display = 'block';
                    
                    // Exibir modal
                    if (departamentoModal) departamentoModal.classList.remove('hidden');
                })
                .catch(function(error) {
                    console.error('Erro ao carregar dados do departamento:', error);
                    showAlert('Erro ao carregar dados do departamento.', 'error');
                })
                .finally(function() {
                    hideLoading();
                });
        });
    });
    
    // Preparar exclusão (handler para botões de exclusão)
    document.querySelectorAll('.delete-departamento').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id || !confirmarExclusao || !confirmDeleteModal) {
                return;
            }
            
            // Anexar o ID ao botão de confirmação
            confirmarExclusao.setAttribute('data-id', id);
            
            // Exibir modal de confirmação
            confirmDeleteModal.classList.remove('hidden');
        });
    });
    
    // Confirmar exclusão
    if (confirmarExclusao) {
        confirmarExclusao.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) {
                return;
            }
            
            showLoading();
            
            axios.delete(`/departamentos/${id}`, {
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(function(response) {
                if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');
                showAlert('Departamento excluído com sucesso!', 'success');
                
                // Recarregar a página para refletir a exclusão
                window.location.reload();
            })
            .catch(function(error) {
                console.error('Erro ao excluir departamento:', error);
                showAlert('Erro ao excluir departamento.', 'error');
            })
            .finally(function() {
                hideLoading();
            });
        });
    }

    // ======= FUNÇÕES UTILITÁRIAS =======

    // Função para mostrar loading
    function showLoading() {
        // Verificar se existe o elemento loading
        let loadingElement = document.getElementById('loading');
        
        if (!loadingElement) {
            // Criar indicador de loading dinamicamente
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
        } else {
            loadingElement.classList.remove('hidden');
        }
    }

    // Função para esconder loading
    function hideLoading() {
        const loadingElement = document.getElementById('loading');
        if (loadingElement) {
            loadingElement.classList.add('hidden');
        }
    }

    // Função para mostrar alertas
    function showAlert(message, type) {
        if (!alertContainer) {
            console.error('Alert container not found');
            return;
        }
        
        alertContainer.classList.remove('hidden');
        
        if (type === 'success') {
            alertContainer.className = 'mb-4 px-5 py-4 bg-green-100 text-green-800 rounded-xl text-sm shadow-md';
        } else {
            alertContainer.className = 'mb-4 px-5 py-4 bg-red-100 text-red-800 rounded-xl text-sm shadow-md';
        }
        
        alertContainer.innerHTML = message;
        
        // Esconder o alerta após 5 segundos
        setTimeout(function() {
            alertContainer.classList.add('hidden');
        }, 5000);
    }
});