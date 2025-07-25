<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Departamentos
        </h2>
    </x-slot>

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <!-- Botão Criar -->
        <div class="flex justify-end mb-6 gap-4">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                ← Dashboard
            </a>
            <button id="btn-criar" type="button"
                class="inline-flex items-center px-5 py-2.5 bg-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Departamento
            </button>
        </div>

        <!-- Alerta de sucesso -->
        <div id="alert-container"
            class="hidden mb-4 px-5 py-4 bg-purple-100 text-purple-800 rounded-xl text-sm shadow-md"></div>

        @if (session('success'))
            <div class="mb-4 px-5 py-4 bg-green-100 text-green-800 rounded-xl text-sm shadow-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-5 py-4 bg-red-100 text-red-800 rounded-xl text-sm shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabela de departamentos -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table
                class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Nome
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Região
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-purple-800 uppercase tracking-wide">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach ($departamentos as $departamento)
                        <tr>
                            <td class="px-6 py-4">{{ $departamento->id }}</td>
                            <td class="px-6 py-4">{{ $departamento->nome }}</td>
                            <td class="px-6 py-4">{{ $departamento->regiao?->nome ?? 'Não definida' }}</td>
                            <td class="px-6 py-4 flex items-center justify-center gap-3">
                                <button
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 view-departamento"
                                    data-id="{{ $departamento->id }}">Visualizar</button>
                                <button
                                    class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 edit-departamento"
                                    data-id="{{ $departamento->id }}">Editar</button>
                                <button
                                    class="text-red-600 hover:text-red-800 font-semibold transition duration-150 delete-departamento"
                                    data-id="{{ $departamento->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edição/Criação de Departamento -->
    <div id="departamento-modal"
        class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <button id="modal-close" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 id="modal-title" class="text-lg font-semibold text-purple-700 mb-4">Novo Departamento</h3>

            <form id="departamento-form" method="POST">
                @csrf
                <input type="hidden" name="id" id="departamento-id">

                <!-- Nome -->
                <div class="mb-4">
                    <label for="departamento-nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" name="nome" id="departamento-nome"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2"
                        required>
                </div>

                <!-- Região (Dropdown) -->
                <div class="mb-4">
                    <label for="departamento-regiao" class="block text-sm font-medium text-gray-700">Região</label>
                    <select name="regiao_id" id="departamento-regiao"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2"
                        required>
                        <option value="">Selecione uma região</option>
                        @foreach ($regioes as $regiao)
                            <option value="{{ $regiao->id }}">{{ $regiao->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="cancelar-modal w-full px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-xl shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                    <button type="submit"
                        class="salvar-modal w-full px-4 py-2 bg-purple-700 text-white text-base font-medium rounded-xl shadow hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="confirm-delete-modal"
        class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <h3 class="text-lg font-semibold text-red-700 mb-4">Confirmar Exclusão</h3>
            <p class="text-gray-700 mb-6">Tem certeza que deseja excluir este departamento?</p>
            <div class="flex justify-end gap-3">
                <button id="cancelar-exclusao"
                    class="w-full px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-xl shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                <button id="confirmar-exclusao"
                    class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-xl shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Excluir</button>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização de Departamento -->
    <div id="departamento-view-modal"
        class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <button class="cancelar-modal absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Detalhes do Departamento</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <div id="view-departamento-nome" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Região</label>
                    <div id="view-departamento-regiao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button"
                    class="cancelar-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    @vite(['resources/js/departamento.js'])
</x-app-layout>
