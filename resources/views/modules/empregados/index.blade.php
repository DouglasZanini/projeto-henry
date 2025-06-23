<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Empregados
        </h2>
    </x-slot>

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <!-- Botão Criar -->
        <div class="flex justify-end mb-6 gap-4">
             <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
               ← Dashboard
            </a> 
            <button type="button" id="btn-criar"
                class="inline-flex items-center px-5 py-2.5 bg-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Empregado
            </button>
        </div>

        <!-- Alerta de sucesso -->
        @if (session('success'))
            <div class="mb-4 px-5 py-4 bg-green-100 text-green-800 rounded-xl text-sm shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabela -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Usuário</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Nome</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Função</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Departamento</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Gerente</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-purple-800 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach ($empregados as $empregado)
                        <tr>
                            <td class="px-6 py-4">{{ $empregado->id }}</td>
                            <td class="px-6 py-4">{{ $empregado->userid }}</td>
                            <td class="px-6 py-4">{{ $empregado->primeiro_nome }} {{ $empregado->ultimo_nome }}</td>
                            <td class="px-6 py-4">{{ $empregado->funcao }}</td>
                            <td class="px-6 py-4">{{ $empregado->departamento?->nome ?? '---' }}</td>
                            <td class="px-6 py-4">{{ $empregado->gerente?->primeiro_nome ?? '---' }}</td>
                            <td class="px-6 py-4 flex items-center justify-center gap-3">
                                <button class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 view-empregado" data-id="{{ $empregado->id }}">Visualizar</button>
                                <button class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 edit-empregado" data-id="{{ $empregado->id }}">Editar</button>
                                <button class="text-red-600 hover:text-red-800 font-semibold transition duration-150 delete-empregado" data-id="{{ $empregado->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edição/Criação de Empregado -->
    <div id="empregado-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative animate-fade-in">
            <button id="modal-close" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            <h3 id="modal-title" class="text-lg font-semibold text-purple-700 mb-4">Novo Empregado</h3>
            
            <form id="empregado-form" method="POST" action="{{ route('empregados.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="primeiro_nome" class="block text-sm font-medium text-gray-700">Primeiro Nome</label>
                        <input type="text" name="primeiro_nome" id="primeiro_nome" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="ultimo_nome" class="block text-sm font-medium text-gray-700">Sobrenome</label>
                        <input type="text" name="ultimo_nome" id="ultimo_nome" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="userid" class="block text-sm font-medium text-gray-700">Usuário</label>
                        <input type="text" name="userid" id="userid" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="admissao" class="block text-sm font-medium text-gray-700">Admissão</label>
                        <input type="date" name="admissao" id="admissao" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                    </div>
                    <div class="col-span-2">
                        <label for="funcao" class="block text-sm font-medium text-gray-700">Função</label>
                        <input type="text" name="funcao" id="funcao" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="salario" class="block text-sm font-medium text-gray-700">Salário</label>
                        <input type="number" step="0.01" name="salario" id="salario" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="comissao" class="block text-sm font-medium text-gray-700">Comissão</label>
                        <input type="number" step="0.01" name="comissao" id="comissao" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="dept_id" class="block text-sm font-medium text-gray-700">Departamento</label>
                        <select name="dept_id" id="dept_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm">
                            <option value="">Selecione um departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="gerente_id" class="block text-sm font-medium text-gray-700">Gerente (ID)</label>
                        <input type="number" name="gerente_id" id="gerente_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm">
                    </div>
                    <div class="col-span-2">
                        <label for="obs" class="block text-sm font-medium text-gray-700">Observações</label>
                        <textarea name="obs" id="obs" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="cancelar-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded-xl shadow hover:bg-purple-800">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Visualização de Empregado -->
    <div id="empregado-view-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative animate-fade-in">
            <button class="cancelar-modal absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Detalhes do Empregado</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Primeiro Nome</label>
                    <div id="view-primeiro_nome" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sobrenome</label>
                    <div id="view-ultimo_nome" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Usuário</label>
                    <div id="view-userid" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Admissão</label>
                    <div id="view-admissao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Função</label>
                    <div id="view-funcao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Salário</label>
                    <div id="view-salario" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Comissão</label>
                    <div id="view-comissao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                    <div id="view-dept_id" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gerente</label>
                    <div id="view-gerente_id" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Observações</label>
                    <div id="view-obs" class="mt-1 p-2 bg-gray-50 rounded-xl border min-h-[60px]">-</div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" class="cancelar-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="confirm-delete-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md p-6 relative animate-fade-in">
            <h3 class="text-lg font-semibold text-red-700 mb-4">Confirmar Exclusão</h3>
            <p class="text-gray-600 mb-6">Tem certeza de que deseja excluir este empregado? Esta ação não pode ser desfeita.</p>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelar-exclusao" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Cancelar</button>
                <button type="button" id="confirmar-exclusao" class="px-4 py-2 bg-red-600 text-white rounded-xl shadow hover:bg-red-700">Excluir</button>
            </div>
        </div>
    </div>

    @vite(['resources/js/empregado.js'])
</x-app-layout>