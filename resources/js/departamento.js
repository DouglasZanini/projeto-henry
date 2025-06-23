document.addEventListener('DOMContentLoaded', () => {
    // Excluir
    document.querySelectorAll('.delete-departamento').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/departamentos/${id}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        });
    });

    // Editar
document.querySelectorAll('.edit-departamento').forEach(button => {
    button.addEventListener('click', async () => {
        const id = button.dataset.id;
        const response = await fetch(`/departamentos/${id}`);
        const data = await response.json();

        document.getElementById('departamento-id').value = data.id;
        document.getElementById('departamento-nome').value = data.nome;
        document.getElementById('departamento-regiao').value = data.regiao_id;

        document.getElementById('departamento-form').action = `/departamentos/${id}`;
        document.getElementById('departamento-form').innerHTML += '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('modal-title').textContent = 'Editar Departamento';
        document.getElementById('departamento-modal').classList.remove('hidden');
    });
});


    // Visualizar
    document.querySelectorAll('.view-departamento').forEach(button => {
        button.addEventListener('click', async () => {
            const id = button.dataset.id;
            const response = await fetch(`/departamentos/${id}`);
            const data = await response.json();

            alert(`Departamento: ${data.nome}\nRegião: ${data.regiao?.nome ?? 'N/A'}`);
        });
    });

    // Cancelar Modal
    document.querySelectorAll('.cancelar-modal').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('departamento-modal').classList.add('hidden');
        });
    });

    // Fechar modal de exclusão
    document.getElementById('cancelar-exclusao')?.addEventListener('click', () => {
        document.getElementById('confirm-delete-modal').classList.add('hidden');
    });

    document.getElementById('modal-close')?.addEventListener('click', () => {
        document.getElementById('departamento-modal').classList.add('hidden');
    });
});
