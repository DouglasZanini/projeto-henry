<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Produtos
        </h2>
    </x-slot>
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-end items-center mb-6 gap-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
            ← Dashboard
        </a>
        <button onclick="openModal()" class="bg-purple-700 text-white px-4 py-2 rounded-xl shadow hover:bg-purple-800">Novo Produto</button>
    </div>

    <!-- Container para alertas -->
    <div id="alert-container" class="hidden"></div>

    @if(session('success'))
        <div class="mb-4 px-5 py-4 bg-green-100 text-green-800 rounded-xl text-sm shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-5 py-4 bg-red-100 text-red-800 rounded-xl text-sm shadow-md">
            {{ session('error') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <thead class="bg-purple-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Nome</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Descrição</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Preço</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Unidades</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-purple-800 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
            @foreach($produtos as $produto)
                <tr>
                    <td class="px-6 py-4">{{ $produto->nome }}</td>
                    <td class="px-6 py-4">{{ $produto->descricao_breve }}</td>
                    <td class="px-6 py-4">{{ $produto->preco_sugerido ? 'R$ ' . number_format($produto->preco_sugerido, 2, ',', '.') : '-' }}</td>
                    <td class="px-6 py-4">{{ $produto->unidades ?? '-' }}</td>
                    <td class="px-6 py-4 flex items-center justify-center gap-3">
                        <button class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 view-produto" data-id="{{ $produto->id }}">Visualizar</button>
                        <button class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 edit-produto" data-id="{{ $produto->id }}">Editar</button>
                        <button class="text-red-600 hover:text-red-800 font-semibold transition duration-150 delete-produto" data-id="{{ $produto->id }}">Excluir</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal criar/editar -->
    <div id="produto-modal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-lg relative">
            <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600 text-xl">×</button>
            <h3 class="text-lg font-semibold mb-4 text-purple-700">Cadastrar Produto</h3>
            <form id="produto-form" method="POST" action="{{ route('produtos.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                    <input name="nome" required class="w-full border rounded-xl p-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição Breve</label>
                    <input name="descricao_breve" class="w-full border rounded-xl p-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preço Sugerido</label>
                    <input name="preco_sugerido" type="number" step="0.01" class="w-full border rounded-xl p-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unidades</label>
                    <input name="unidades" class="w-full border rounded-xl p-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded-xl hover:bg-purple-800">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de visualização de produto (NOVO) -->
    <div id="produto-view-modal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative animate-fade-in">
            <button class="cancelar-modal absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Detalhes do Produto</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <div id="view-produto-nome" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <div id="view-produto-descricao" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Preço</label>
                    <div id="view-produto-preco" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unidades</label>
                    <div id="view-produto-unidades" class="mt-1 p-2 bg-gray-50 rounded-xl border">-</div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" class="cancelar-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md relative">
            <button onclick="closeDeleteModal()" class="absolute top-3 right-4 text-gray-600 text-xl">×</button>
            <h3 class="text-lg font-semibold mb-4 text-red-700">Confirmar Exclusão</h3>
            <p class="text-sm text-gray-700 mb-6">Tem certeza de que deseja excluir este produto? Esta ação não poderá ser desfeita.</p>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

@vite(['resources/js/produto.js'])
</x-app-layout>