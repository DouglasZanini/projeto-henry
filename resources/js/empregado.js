document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('empregado-modal');
  const viewModal = document.getElementById('empregado-view-modal');
  const deleteModal = document.getElementById('confirm-delete-modal');

  const form = document.getElementById('empregado-form');
  const modalTitle = document.getElementById('modal-title');
  const btnClose = document.getElementById('modal-close');

  let currentEmpregadoId = null;

  // Abrir modal para criar novo empregado
  document.getElementById('btn-criar').addEventListener('click', () => {
    currentEmpregadoId = null;
    modalTitle.textContent = 'Novo Empregado';
    form.reset();
    form.action = '/empregados'; // rota para store
    form.method = 'POST';
    modal.classList.remove('hidden');
  });

  // Fechar modal
  btnClose.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  // Cancelar modal (botão cancelar)
  document.querySelectorAll('.cancelar-modal').forEach(btn =>
    btn.addEventListener('click', () => {
      modal.classList.add('hidden');
      deleteModal.classList.add('hidden');
      viewModal.classList.add('hidden');
    })
  );

  // Editar empregado
  document.querySelectorAll('.edit-empregado').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.getAttribute('data-id');
      currentEmpregadoId = id;
      modalTitle.textContent = 'Editar Empregado';
      try {
        const res = await fetch(`/empregados/${id}/edit`);
        if (!res.ok) throw new Error('Erro ao carregar empregado');
        const data = await res.json();

        // Preenche o form com dados do empregado
        form.action = `/empregados/${id}`;
        form.method = 'POST';

        form.querySelector('input[name="_method"]').value = 'PUT'; // method spoofing para PUT
        form.querySelector('input[name="ultimo_nome"]').value = data.ultimo_nome;
        form.querySelector('input[name="primeiro_nome"]').value = data.primeiro_nome;
        form.querySelector('input[name="userid"]').value = data.userid;
        form.querySelector('input[name="admissao"]').value = data.admissao;
        form.querySelector('textarea[name="obs"]').value = data.obs;
        form.querySelector('input[name="gerente_id"]').value = data.gerente_id ?? '';
        form.querySelector('input[name="funcao"]').value = data.funcao;
        form.querySelector('select[name="dept_id"]').value = data.dept_id ?? '';
        form.querySelector('input[name="salario"]').value = data.salario;
        form.querySelector('input[name="comissao"]').value = data.comissao;

        modal.classList.remove('hidden');
      } catch (error) {
        alert(error.message);
      }
    });
  });

  // Visualizar empregado
  document.querySelectorAll('.view-empregado').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.getAttribute('data-id');
      try {
        const res = await fetch(`/empregados/${id}`);
        if (!res.ok) throw new Error('Erro ao carregar empregado');
        const data = await res.json();

        // Preenche os campos do modal de visualização
        viewModal.querySelector('#view-ultimo_nome').textContent = data.ultimo_nome;
        viewModal.querySelector('#view-primeiro_nome').textContent = data.primeiro_nome;
        viewModal.querySelector('#view-userid').textContent = data.userid;
        viewModal.querySelector('#view-admissao').textContent = data.admissao;
        viewModal.querySelector('#view-obs').textContent = data.obs || '-';
        viewModal.querySelector('#view-gerente_id').textContent = data.gerente_id ?? '-';
        viewModal.querySelector('#view-funcao').textContent = data.funcao;
        viewModal.querySelector('#view-dept_id').textContent = data.dept_nome ?? '-';
        viewModal.querySelector('#view-salario').textContent = data.salario;
        viewModal.querySelector('#view-comissao').textContent = data.comissao;

        viewModal.classList.remove('hidden');
      } catch (error) {
        alert(error.message);
      }
    });
  });

  // Excluir empregado - abrir modal de confirmação
  document.querySelectorAll('.delete-empregado').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      currentEmpregadoId = id;
      deleteModal.classList.remove('hidden');
    });
  });

  // Cancelar exclusão
  document.getElementById('cancelar-exclusao').addEventListener('click', () => {
    deleteModal.classList.add('hidden');
  });

  // Confirmar exclusão
  document.getElementById('confirmar-exclusao').addEventListener('click', async () => {
    if (!currentEmpregadoId) return;
    try {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const res = await fetch(`/empregados/${currentEmpregadoId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      });

      if (!res.ok) throw new Error('Erro ao excluir empregado');

      // Após exclusão, recarregar a página para atualizar lista
      location.reload();
    } catch (error) {
      alert(error.message);
    }
  });
});
