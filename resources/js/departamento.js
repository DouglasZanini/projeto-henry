document.addEventListener('DOMContentLoaded', function () {
    // ======= VERIFICAÇÃO DE ELEMENTOS =======
    const departamentoModal = document.getElementById('departamento-modal');
    const modalClose = document.getElementById('modal-close');
    const departamentoForm = document.getElementById('departamento-form');
    const inputNome = document.getElementById('departamento-nome');
    const inputRegiao = document.getElementById('departamento-regiao');
    const inputId = document.getElementById('departamento-id');
    const modalTitle = document.getElementById('modal-title');
    const confirmDeleteModal = document.getElementById('confirm-delete-modal');
    const cancelarExclusao = document.getElementById('cancelar-exclusao');
    const confirmarExclusao = document.getElementById('confirmar-exclusao');
    const alertContainer = document.getElementById('alert-container');
    const btnCriar = document.getElementById('btn-criar');

    let currentDepartamentoId = null;

    // ======= HANDLERS DE EVENTOS =======

    // Abrir modal para criar departamento
    if (btnCriar) {
        btnCriar.addEventListener('click', function () {
            currentDepartamentoId = null;
            if (modalTitle) modalTitle.textContent = 'Novo Departamento';
            if (departamentoForm) departamentoForm.reset();
            if (inputId) inputId.value = '';
            if (departamentoModal) departamentoModal.classList.remove('hidden');
        });
    }

    // Handler para botões de cancelar em todos os modais
    document.querySelectorAll('.cancelar-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modal = this.closest('.fixed');
            if (modal) modal.classList.add('hidden');
        });
    });

    // Fechar modal de departamento
    if (modalClose) {
        modalClose.addEventListener('click', function () {
            if (departamentoModal) departamentoModal.classList.add('hidden');
        });
    }

    // Fechar modal de confirmação de exclusão
    if (cancelarExclusao) {
        cancelarExclusao.addEventListener('click', function () {
            if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');
        });
    }

    // ======= FUNÇÕES CRUD =======

    // Salvar departamento (evento de submit do form)
    if (departamentoForm) {
        departamentoForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const id = inputId?.value;
            const nome = inputNome?.value;
            const regiao_id = inputRegiao?.value;

            // Validação básica
            if (!nome || !regiao_id) {
                showAlert('Por favor, preencha todos os campos', 'error');
                return;
            }

            const url = id ? `/departamentos/${id}` : '/departamentos';
            const method = id ? 'PUT' : 'POST';

            showLoading(true);

            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('regiao_id', regiao_id);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
            if (id) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(function (data) {
                    if (departamentoModal) departamentoModal.classList.add('hidden');
                    showAlert(data.message || 'Departamento salvo com sucesso!', 'success');

                    // Recarregar a página para mostrar a atualização
                    window.location.reload();
                })
                .catch(function (error) {
                    console.error('Erro ao salvar departamento:', error);
                    showAlert('Erro ao salvar departamento. Por favor, tente novamente.', 'error');
                })
                .finally(function () {
                    showLoading(false);
                });
        });
    }

    // Visualizar departamento
    document.querySelectorAll('.view-departamento').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (!id) return;

            const viewModal = document.getElementById('departamento-view-modal');
            if (!viewModal) return;

            showLoading(true);

            fetch(`/departamentos/${id}`)
                .then(response => response.json())
                .then(function (data) {
                    // Preencher os campos do modal de visualização
                    document.getElementById('view-departamento-nome').textContent = data.nome || '-';
                    document.getElementById('view-departamento-regiao').textContent = data.regiao?.nome || '-';

                    // Mostrar o modal
                    viewModal.classList.remove('hidden');
                })
                .catch(function (error) {
                    console.error('Erro ao carregar detalhes do departamento:', error);
                    showAlert('Erro ao carregar detalhes do departamento.', 'error');
                })
                .finally(function () {
                    showLoading(false);
                });
        });
    });

    // Editar departamento
    document.querySelectorAll('.edit-departamento').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (!id || !departamentoModal || !modalTitle || !inputNome || !inputRegiao) {
                return;
            }

            showLoading(true);

            fetch(`/departamentos/${id}`)
                .then(response => response.json())
                .then(function (data) {
                    // Preencher o formulário para edição
                    if (modalTitle) modalTitle.textContent = 'Editar Departamento';
                    if (inputId) inputId.value = data.id;
                    if (inputNome) inputNome.value = data.nome;
                    if (inputRegiao) inputRegiao.value = data.regiao_id;

                    currentDepartamentoId = data.id;

                    // Exibir modal
                    if (departamentoModal) departamentoModal.classList.remove('hidden');
                })
                .catch(function (error) {
                    console.error('Erro ao carregar dados do departamento:', error);
                    showAlert('Erro ao carregar dados do departamento.', 'error');
                })
                .finally(function () {
                    showLoading(false);
                });
        });
    });

    // Preparar exclusão
    document.querySelectorAll('.delete-departamento').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (!id || !confirmarExclusao || !confirmDeleteModal) {
                return;
            }

            // Anexar o ID ao botão de confirmação
            confirmarExclusao.setAttribute('data-id', id);
            currentDepartamentoId = id;

            // Exibir modal de confirmação
            confirmDeleteModal.classList.remove('hidden');
        });
    });

    // Confirmar exclusão
    if (confirmarExclusao) {
        confirmarExclusao.addEventListener('click', function () {
            const id = currentDepartamentoId || this.getAttribute('data-id');
            if (!id) return;

            showLoading(true);

            fetch(`/departamentos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(function (data) {
                    if (confirmDeleteModal) confirmDeleteModal.classList.add('hidden');

                    if (data.success) {
                        showAlert(data.message || 'Departamento excluído com sucesso!', 'success');
                        // Recarregar a página para refletir a exclusão
                        window.location.reload();
                    } else {
                        showAlert(data.message || 'Erro ao excluir departamento.', 'error');
                    }
                })
                .catch(function (error) {
                    console.error('Erro ao excluir departamento:', error);
                    showAlert('Erro ao excluir departamento.', 'error');
                })
                .finally(function () {
                    hideLoading();
                });
        });
    }

    // ======= FUNÇÕES UTILITÁRIAS =======

    function showLoading(show = true) {
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

    function hideLoading() {
        showLoading(false);
    }

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
        setTimeout(function () {
            alertContainer.classList.add('hidden');
        }, 5000);
    }
});