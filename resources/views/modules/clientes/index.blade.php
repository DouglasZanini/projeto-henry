<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Clientes
        </h2>
    </x-slot>

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <!-- Botão e Alerta -->
        <div class="flex justify-end mb-6 gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
               ← Dashboard
            </a>  
            <button id="btn-criar" class="bg-purple-700 text-white px-5 py-2 rounded-lg shadow hover:bg-purple-800">Novo Cliente</button>
        </div>

        <!-- Alerta de sucesso -->
        <div id="alert-container" class="hidden mb-4 px-5 py-4 bg-purple-100 text-purple-800 rounded-xl text-sm shadow-md"></div>

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

        <!-- Tabela de clientes -->
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">Nome</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">Fone</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">Cidade</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800">Região</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-purple-800">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td class="px-6 py-4">{{ $cliente->id }}</td>
                            <td class="px-6 py-4">{{ $cliente->nome }}</td>
                            <td class="px-6 py-4">{{ $cliente->fone }}</td>
                            <td class="px-6 py-4">{{ $cliente->cidade }}</td>
                            <td class="px-6 py-4">{{ $cliente->estado }}</td>
                            <td class="px-6 py-4">{{ $cliente->regiao?->nome ?? 'N/D' }}</td>
                            <td class="px-6 py-4 flex items-center justify-center gap-3">
                                <button class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 view-cliente" data-id="{{ $cliente->id }}">Visualizar</button>
                                <button class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 edit-cliente" data-id="{{ $cliente->id }}">Editar</button>
                                <button class="text-red-600 hover:text-red-800 font-semibold transition duration-150 delete-cliente" data-id="{{ $cliente->id }}">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edição/Criação de Cliente -->
    <div id="cliente-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <button id="modal-close" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 id="modal-title" class="text-lg font-semibold text-purple-700 mb-4">Novo Cliente</h3>

            <form id="cliente-form" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="id" id="cliente-id">

                <!-- Nome -->
                <div class="mb-4">
                    <label for="cliente-nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" name="nome" id="cliente-nome" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2" required>
                </div>

                <!-- Fone -->
                <div class="mb-4">
                    <label for="cliente-fone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="text" name="fone" id="cliente-fone" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                </div>
                
                <!-- Endereço -->
                <div class="mb-4">
                    <label for="cliente-endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
                    <input type="text" name="endereco" id="cliente-endereco" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                </div>

                <!-- Cidade -->
                <div class="mb-4">
                    <label for="cliente-cidade" class="block text-sm font-medium text-gray-700">Cidade</label>
                    <input type="text" name="cidade" id="cliente-cidade" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <label for="cliente-estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <input type="text" name="estado" id="cliente-estado" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2" maxlength="2">
                </div>

                <!-- CEP -->
                <div class="mb-4">
                    <label for="cliente-cep" class="block text-sm font-medium text-gray-700">CEP</label>
                    <input type="text" name="cep" id="cliente-cep" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                </div>

                <!-- Região (Dropdown) -->
                <div class="mb-4">
                    <label for="cliente-regiao" class="block text-sm font-medium text-gray-700">Região</label>
                    <select name="regiao_id" id="cliente-regiao" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                        <option value="">Selecione uma região</option>
                        @foreach ($regioes ?? [] as $regiao)
                            <option value="{{ $regiao->id }}">{{ $regiao->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Limite de Crédito -->
                <div class="mb-4">
                    <label for="cliente-limite" class="block text-sm font-medium text-gray-700">Limite de Crédito</label>
                    <input type="number" step="0.01" name="limitecredito" id="cliente-limite" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm px-4 py-2">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="cancelar-modal w-full px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-xl shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                    <button type="submit" class="salvar-modal w-full px-4 py-2 bg-purple-700 text-white text-base font-medium rounded-xl shadow hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Visualização de Cliente -->
    <div id="cliente-view-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <button class="cancelar-modal absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Detalhes do Cliente</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <div id="view-cliente-nome" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telefone</label>
                    <div id="view-cliente-fone" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Endereço</label>
                    <div id="view-cliente-endereco" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cidade/Estado</label>
                    <div id="view-cliente-cidade-estado" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CEP</label>
                    <div id="view-cliente-cep" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Região</label>
                    <div id="view-cliente-regiao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Limite de Crédito</label>
                    <div id="view-cliente-limite" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" class="cancelar-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="confirm-delete-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <h3 class="text-lg font-semibold text-red-700 mb-4">Confirmar Exclusão</h3>
            <p class="text-gray-700 mb-6">Tem certeza que deseja excluir este cliente?</p>
            <div class="flex justify-end gap-3">
                <button id="cancelar-exclusao" class="w-full px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-xl shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                <button id="confirmar-exclusao" class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-xl shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Excluir</button>
            </div>
        </div>
    </div>

    @vite(['resources/js/cliente.js'])
</x-app-layout>