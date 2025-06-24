document.addEventListener('DOMContentLoaded', function() {
    // ======= VERIFICAÇÃO DE ELEMENTOS =======
    const clienteModal = document.getElementById('cliente-modal');
    const viewModal = document.getElementById('cliente-view-modal');
    const confirmDeleteModal = document.getElementById('confirm-delete-modal');
    const modalClose = document.getElementById('modal-close');
    const clienteForm = document.getElementById('cliente-form');
    const modalTitle = document.getElementById('modal-title');
    const btnCriar = document.getElementById('btn-criar');
    const cancelarExclusao = document.getElementById('cancelar-exclusao');
    const confirmarExclusao = document.getElementById('confirmar-exclusao');
    const alertContainer = document.getElementById('alert-container');

    let currentClienteId = null;

    // ======= HANDLERS DE EVENTOS =======

    // Abrir modal para criar cliente
    if (btnCriar) {
        btnCriar.addEventListener('click', function() {
            currentClienteId = null;
            if (modalTitle) modalTitle.textContent = 'Novo Cliente';
            if (clienteForm) {
                clienteForm.reset();
                clienteForm.action = '/clientes';
                clienteForm.querySelector('input[name="_method"]').value = 'POST';
            }
            if (clienteModal) clienteModal.classList.remove('hidden');
        });
    }

    // Handler para botões de cancelar em todos os modais
    document.querySelectorAll('.cancelar-modal').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.fixed');
            if (modal) modal.classList.add('hidden');
        });
    });
    
    // Fechar modal de cliente
    if (modalClose) {
        modalClose.addEventListener('click', function() {
            if (clienteModal) clienteModal.classList.add('hidden');
        });
    }
    
    // Fechar modal de confirmação de exclusão
    if (cancelarExclusao) {
        cancelarExclusao.addEventListener('click', function() {
            if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');
        });
    }

    // ======= FUNÇÕES CRUD =======
    
    // Salvar cliente (evento de submit do form)
    if (clienteForm) {
        clienteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const method = currentClienteId ? 'PUT' : 'POST';
            const url = currentClienteId ? `/clientes/${currentClienteId}` : '/clientes';
            
            fetch(url, {
                method: method === 'PUT' ? 'POST' : method, // Para PUT, usamos POST com _method
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (clienteModal) clienteModal.classList.add('hidden');
                
                if (data.success) {
                    showAlert(data.message || 'Cliente salvo com sucesso!', 'success');
                    
                    // Atualizar a tabela sem refresh da página
                    updateTable();
                } else {
                    showAlert(data.message || 'Erro ao salvar cliente', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao processar requisição', 'error');
            });
        });
    }
    
    // Visualizar cliente
    document.querySelectorAll('.view-cliente').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;
            
            fetch(`/clientes/${id}`)
                .then(response => response.json())
                .then(data => {
                    // Preencher os campos do modal de visualização
                    document.getElementById('view-cliente-nome').textContent = data.nome || '-';
                    document.getElementById('view-cliente-fone').textContent = data.fone || '-';
                    document.getElementById('view-cliente-endereco').textContent = data.endereco || '-';
                    document.getElementById('view-cliente-cidade-estado').textContent = 
                        `${data.cidade || '-'} / ${data.estado || '-'}`;
                    document.getElementById('view-cliente-cep').textContent = data.cep || '-';
                    document.getElementById('view-cliente-regiao').textContent = data.regiao?.nome || '-';
                    document.getElementById('view-cliente-limite').textContent = 
                        data.limitecredito ? `R$ ${parseFloat(data.limitecredito).toLocaleString('pt-BR', {minimumFractionDigits: 2})}` : '-';
                    
                    // Mostrar o modal
                    if (viewModal) viewModal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erro ao carregar detalhes do cliente:', error);
                    showAlert('Erro ao carregar detalhes do cliente', 'error');
                });
        });
    });
    
    // Editar cliente
    document.querySelectorAll('.edit-cliente').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;
            
            currentClienteId = id;
            
            fetch(`/clientes/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Preencher o formulário para edição
                    if (modalTitle) modalTitle.textContent = 'Editar Cliente';
                    if (clienteForm) {
                        clienteForm.querySelector('input[name="_method"]').value = 'PUT';
                        clienteForm.querySelector('input[name="nome"]').value = data.nome || '';
                        clienteForm.querySelector('input[name="fone"]').value = data.fone || '';
                        clienteForm.querySelector('input[name="endereco"]').value = data.endereco || '';
                        clienteForm.querySelector('input[name="cidade"]').value = data.cidade || '';
                        clienteForm.querySelector('input[name="estado"]').value = data.estado || '';
                        clienteForm.querySelector('input[name="cep"]').value = data.cep || '';
                        clienteForm.querySelector('input[name="limitecredito"]').value = data.limitecredito || '';
                        
                        const regiaoSelect = clienteForm.querySelector('select[name="regiao_id"]');
                        if (regiaoSelect) {
                            regiaoSelect.value = data.regiao_id || '';
                        }
                    }
                    
                    // Exibir modal
                    if (clienteModal) clienteModal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erro ao carregar dados do cliente:', error);
                    showAlert('Erro ao carregar dados do cliente', 'error');
                });
        });
    });
    
    // Preparar exclusão
    document.querySelectorAll('.delete-cliente').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;
            
            currentClienteId = id;
            
            // Exibir modal de confirmação
            if (confirmDeleteModal) confirmDeleteModal.classList.remove('hidden');
        });
    });
    
    // Confirmar exclusão
    if (confirmarExclusao) {
        confirmarExclusao.addEventListener('click', function() {
            if (!currentClienteId) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/clientes/${currentClienteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');
                
                if (data.success) {
                    showAlert(data.message || 'Cliente excluído com sucesso!', 'success');
                    // Atualizar a tabela sem refresh
                    updateTable();
                } else {
                    showAlert(data.message || 'Erro ao excluir cliente', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir cliente:', error);
                showAlert('Erro ao excluir cliente', 'error');
            });
        });
    }

    // ======= FUNÇÕES UTILITÁRIAS =======

    // Mostrar alerta
    function showAlert(message, type) {
        if (!alertContainer) return;
        
        alertContainer.classList.remove('hidden');
        
        if (type === 'success') {
            alertContainer.className = 'mb-4 px-5 py-4 bg-green-100 text-green-800 rounded-xl text-sm shadow-md';
        } else {
            alertContainer.className = 'mb-4 px-5 py-4 bg-red-100 text-red-800 rounded-xl text-sm shadow-md';
        }
        
        alertContainer.innerHTML = message;
        
        // Scroll para o topo para garantir que o alerta seja visível
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Atualizar tabela sem recarregar a página
    function updateTable() {
        fetch('/clientes?partial=true')
            .then(response => response.text())
            .then(html => {
                // Atualiza só o conteúdo da tabela, não a página inteira
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                const newTable = tempDiv.querySelector('table');
                if (newTable) {
                    const currentTable = document.querySelector('table');
                    if (currentTable) {
                        currentTable.innerHTML = newTable.innerHTML;
                    }
                }
                
                // Reaplica os event listeners nos novos botões
                attachEventListeners();
            })
            .catch(error => {
                console.error('Erro ao atualizar tabela:', error);
            });
    }
    
    // Reaplicar event listeners após atualização da tabela
    function attachEventListeners() {
        // Visualizar cliente
        document.querySelectorAll('.view-cliente').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                
                fetch(`/clientes/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        // Preencher os campos do modal de visualização
                        document.getElementById('view-cliente-nome').textContent = data.nome || '-';
                        document.getElementById('view-cliente-fone').textContent = data.fone || '-';
                        document.getElementById('view-cliente-endereco').textContent = data.endereco || '-';
                        document.getElementById('view-cliente-cidade-estado').textContent = 
                            `${data.cidade || '-'} / ${data.estado || '-'}`;
                        document.getElementById('view-cliente-cep').textContent = data.cep || '-';
                        document.getElementById('view-cliente-regiao').textContent = data.regiao?.nome || '-';
                        document.getElementById('view-cliente-limite').textContent = 
                            data.limitecredito ? `R$ ${parseFloat(data.limitecredito).toLocaleString('pt-BR', {minimumFractionDigits: 2})}` : '-';
                        
                        // Mostrar o modal
                        if (viewModal) viewModal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Erro ao carregar detalhes do cliente:', error);
                        showAlert('Erro ao carregar detalhes do cliente', 'error');
                    });
            });
        });
        
        // Editar cliente
        document.querySelectorAll('.edit-cliente').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                
                currentClienteId = id;
                
                fetch(`/clientes/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        // Preencher o formulário para edição
                        if (modalTitle) modalTitle.textContent = 'Editar Cliente';
                        if (clienteForm) {
                            clienteForm.querySelector('input[name="_method"]').value = 'PUT';
                            clienteForm.querySelector('input[name="nome"]').value = data.nome || '';
                            clienteForm.querySelector('input[name="fone"]').value = data.fone || '';
                            clienteForm.querySelector('input[name="endereco"]').value = data.endereco || '';
                            clienteForm.querySelector('input[name="cidade"]').value = data.cidade || '';
                            clienteForm.querySelector('input[name="estado"]').value = data.estado || '';
                            clienteForm.querySelector('input[name="cep"]').value = data.cep || '';
                            clienteForm.querySelector('input[name="limitecredito"]').value = data.limitecredito || '';
                            
                            const regiaoSelect = clienteForm.querySelector('select[name="regiao_id"]');
                            if (regiaoSelect) {
                                regiaoSelect.value = data.regiao_id || '';
                            }
                        }
                        
                        // Exibir modal
                        if (clienteModal) clienteModal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Erro ao carregar dados do cliente:', error);
                        showAlert('Erro ao carregar dados do cliente', 'error');
                    });
            });
        });
        
        // Preparar exclusão
        document.querySelectorAll('.delete-cliente').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                
                currentClienteId = id;
                
                // Exibir modal de confirmação
                if (confirmDeleteModal) confirmDeleteModal.classList.remove('hidden');
            });
        });
    }
});