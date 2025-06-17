<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Vendas
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">

    <h1 class="text-2xl font-bold mb-6 text-purple-700">Registrar Nova Venda</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <form action="" method="POST">
        @csrf

        <div class="mb-4">
            <label for="cliente_id" class="block font-semibold mb-1">Cliente</label>
            <select name="cliente_id" id="cliente_id" required class="w-full border rounded p-2">
                <option value="">Selecione um cliente</option>
               
                    <option value="">tes</option>
                
            </select>
            @error('cliente_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="vendedor_id" class="block font-semibold mb-1">Vendedor</label>
            <select name="vendedor_id" id="vendedor_id" required class="w-full border rounded p-2">
                <option value="">Selecione um vendedor</option>
                
                    <option value="">tes</option>
                
            </select>
            @error('vendedor_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="tipo_pagamento" class="block font-semibold mb-1">Tipo de Pagamento</label>
            <input type="text" name="tipo_pagamento" id="tipo_pagamento" class="w-full border rounded p-2" placeholder="Ex: Crédito, Dinheiro...">
        </div>

        <h2 class="font-semibold text-lg mb-3 mt-6">Itens da Venda</h2>

        <div id="produtos-container">
            <div class="produto-item mb-4 p-4 border rounded flex gap-4 items-end">

                <div class="flex-1">
                    <label class="block font-semibold mb-1">Produto</label>
                    <select name="produtos[0][produto_id]" class="w-full border rounded p-2 produto-select" required>
                        <option value="">Selecione o produto</option>
                        
                            <option value="">tes</option>
                       
                    </select>
                </div>

                <div class="w-24">
                    <label class="block font-semibold mb-1">Quantidade</label>
                    <input type="number" name="produtos[0][quantidade]" min="1" class="w-full border rounded p-2" required>
                </div>

                <div class="w-32">
                    <label class="block font-semibold mb-1">Preço (R$)</label>
                    <input type="number" name="produtos[0][preco]" min="0" step="0.01" class="w-full border rounded p-2" required>
                </div>

                <button type="button" class="remove-produto text-red-600 font-bold text-xl" title="Remover produto">&times;</button>
            </div>
        </div>

        <button
            type="button"
            id="add-produto"
            class="mt-2 mb-6 px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800 transition"
        >
            + Adicionar Produto
        </button>

        <div>
            <button
                type="submit"
                class="w-full py-3 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition"
            >
                Registrar Venda
            </button>
        </div>
    </form>
</div>


@vite(['resources/js/departamento.js'])
</x-app-layout>
