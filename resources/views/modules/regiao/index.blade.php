<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Regiões
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="flex justify-end mb-6 gap-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition">
                ← Dashboard
            </a>
            <button onclick="document.getElementById('modal').classList.remove('hidden')"
                class="inline-flex items-center px-5 py-2.5 bg-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition">
                Nova Região
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-purple-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nome</th>
                        <th class="px-4 py-2 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach($regioes as $regiao)
                        <tr class="border-t hover:bg-purple-50 transition">
                            <td class="px-4 py-2">{{ $regiao->id }}</td>
                            <td class="px-4 py-2">{{ $regiao->nome }}</td>
                            <td class="px-4 py-2 text-center flex justify-center gap-3">
                           
                                <a href="#"
                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm"
                                    onclick="abrirModalEdicao({{ $regiao->id }}, '{{ $regiao->nome }}')">
                                        Editar
                                </a>                                
                                <form method="POST" action="{{ route('regiao.destroy', $regiao->id) }}">
                                    @csrf
                                    @method('DELETE')
                                  <button
                                    type="button"
                                    class="btn-abrir-modal-excluir text-red-600 hover:text-red-800 font-medium text-sm"
                                    data-route="{{ route('regiao.destroy', $regiao->id) }}">
                                    Excluir
                                    </button>


                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

        <!-- MODAL DE EDIÇÃO -->
    <div id="modal-editar" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-4">Editar Região</h2>
            <form id="form-editar" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editar-nome" class="block text-sm font-medium text-gray-700">Nome da Região</label>
                    <input type="text" name="nome" id="editar-nome" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500" />
                    <div id="erro-editar-nome" class="text-sm text-red-600 mt-2 hidden"></div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="document.getElementById('modal-editar').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md bg-purple-700 text-white font-semibold hover:bg-purple-800 transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL DE CRIAÇÃO -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-4">Nova Região</h2>
            <form action="{{ route('regiao.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Região</label>
                    <input type="text" name="nome" id="nome" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500" />
                    @error('nome')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="document.getElementById('modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md bg-purple-700 text-white font-semibold hover:bg-purple-800 transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="confirm-delete-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md p-6 relative animate-fade-in">
            <h3 class="text-lg font-semibold text-red-700 mb-4">Confirmar Exclusão</h3>
            <p class="text-gray-600 mb-6">Tem certeza de que deseja excluir esta Região? Esta ação não pode ser desfeita.</p>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelar-exclusao" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Cancelar</button>
                <button type="button" id="confirmar-exclusao" class="px-4 py-2 bg-red-600 text-white rounded-xl shadow hover:bg-red-700">
                    Excluir
                </button>


            </div>
        </div>
    </div>
<script>
    function abrirModalEdicao(id, nome) {
        // Preenche o campo
        document.getElementById('editar-nome').value = nome;

        // Atualiza a action do form com a rota correta
        const form = document.getElementById('form-editar');
        form.action = `/regiao/${id}`;

        // Exibe o modal
        document.getElementById('modal-editar').classList.remove('hidden');
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirm-delete-modal');
        const form = document.getElementById('form-excluir-regiao');
        let deleteUrl = '';

        // Abrir modal ao clicar no botão de excluir
        document.querySelectorAll('.btn-abrir-modal-excluir').forEach(button => {
            button.addEventListener('click', () => {
                deleteUrl = button.dataset.route;
                modal.classList.remove('hidden');
            });
        });

        // Cancelar exclusão
        document.getElementById('cancelar-exclusao').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Confirmar exclusão
        document.getElementById('confirmar-exclusao').addEventListener('click', () => {
            form.setAttribute('action', deleteUrl);
            form.submit();
        });
    });
</script>

<form id="form-excluir-regiao" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

</x-app-layout>
