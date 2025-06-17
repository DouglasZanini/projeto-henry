<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Vendas
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">

        <h1 class="text-2xl font-bold mb-6 text-purple-700">Registrar Nova Venda</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <form action="{{ route('vendas.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="cliente_id" class="block font-semibold mb-1">Cliente</label>
                <select name="cliente_id" id="cliente_id" required class="w-full border rounded p-2">
                    <option value="">Selecione um cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome }} {{ $cliente->credito ? '- Crédito: ' . $cliente->credito : '' }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="vendedor_id" class="block font-semibold mb-1">Vendedor</label>
                <select name="vendedor_id" id="vendedor_id" required class="w-full border rounded p-2">
                    <option value="">Selecione um vendedor</option>
                    @foreach ($vendedores as $vendedor)
                        <option value="{{ $vendedor->id }}" {{ old('vendedor_id') == $vendedor->id ? 'selected' : '' }}>
                            {{ $vendedor->primeiro_nome }} {{ $vendedor->ultimo_nome }}
                            @if ($vendedor->comissao)
                                ({{ number_format($vendedor->comissao, 2) }}%)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('vendedor_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tipo_pagamento" class="block font-semibold mb-1">Tipo de Pagamento</label>
                <select name="tipo_pagamento" id="tipo_pagamento" required class="w-full border rounded p-2">
                    <option value="">Selecione o tipo de pagamento</option>
                    <option value="CASH" {{ old('tipo_pagamento') == 'CASH' ? 'selected' : '' }}>Dinheiro (CASH)
                    </option>
                    <option value="CREDIT" {{ old('tipo_pagamento') == 'CREDIT' ? 'selected' : '' }}>Crédito (CREDIT)
                    </option>
                </select>
                @error('tipo_pagamento')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h2 class="font-semibold text-lg mb-3 mt-6">Itens da Venda</h2>

            <div id="produtos-container">
                <div class="produto-item mb-4 p-4 border rounded flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block font-semibold mb-1">Produto</label>
                        <select name="produtos[0][produto_id]" class="w-full border rounded p-2 produto-select"
                            required>
                            <option value="">Selecione o produto</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->id }}" data-preco="{{ $produto->preco_sugerido }}">
                                    {{ $produto->nome }} - {{ $produto->unidades }} - R$
                                    {{ number_format($produto->preco_sugerido, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-20">
                        <label class="block font-semibold mb-1">Qtd</label>
                        <input type="number" name="produtos[0][quantidade]" min="1" value="1"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="w-28">
                        <label class="block font-semibold mb-1">Preço Unit.</label>
                        <input type="number" name="produtos[0][preco]" min="0" step="0.01"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="w-28">
                        <label class="block font-semibold mb-1">Total</label>
                        <div class="preco-total font-medium text-gray-800">R$ 0,00</div>
                    </div>

                    <button type="button" class="remove-produto text-red-600 font-bold text-xl self-center"
                        title="Remover produto">&times;</button>
                </div>
            </div>

            <button type="button" id="add-produto"
                class="mt-2 mb-6 px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800 transition">
                + Adicionar Produto
            </button>

            <div class="flex justify-between items-center mb-6 border-t border-gray-200 pt-4 mt-4">
                <button type="submit"
                    class="px-8 py-3 bg-purple-700 text-white font-semibold text-lg rounded-lg hover:bg-purple-800 transition shadow-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Registrar Venda
                </button>

                <input type="hidden" id="valor_total" name="valor_total" value="0.00">

                <div class="text-right">
                    <div class="text-lg font-semibold">Total da Venda:</div>
                    <div id="total-geral" class="text-xl font-bold text-green-700">R$ 0,00</div>
                </div>
            </div>
        </form>
    </div>

    @vite(['resources/js/vendas.js'])

</x-app-layout>
