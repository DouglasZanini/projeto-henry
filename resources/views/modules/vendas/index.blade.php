<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Vendas
        </h2>
    </x-slot>

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <!-- Botão Criar -->
        <div class="flex justify-end mb-6 gap-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                ← Dashboard
            </a>
            <a href="{{ route('vendas.create') }}" class="inline-flex items-center px-5 py-2.5 bg-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Venda
            </a>
        </div>

        <!-- Alerta de sucesso -->
        <div id="alert-container" class="hidden mb-4">
            <div id="alert-content" class="px-4 py-3 rounded relative"></div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <!-- Tabela de vendas -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Pedido #</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Cliente</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Data</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Vendedor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-purple-800 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach ($vendas as $venda)
                        <tr>
                            <td class="px-6 py-4">{{ $venda->id }}</td>
                            <td class="px-6 py-4">{{ $venda->cliente->nome }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($venda->data_ordenamento)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $venda->vendedor->primeiro_nome }} {{ $venda->vendedor->ultimo_nome }}</td>
                            <td class="px-6 py-4">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($venda->ordem_cheia == 'Y')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completa</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Em processamento</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 view-venda" data-id="{{ $venda->id }}">Visualizar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <div class="mt-4">
            {{ $vendas->links() }}
        </div>
    </div>

    <!-- Modal de Visualização -->
    <div id="view-modal" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl p-6 relative animate-fade-in">
            <button id="close-view-modal" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Detalhes da Venda</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Informações Gerais</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Pedido #:</span>
                            <span id="view-id" class="ml-1"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Data do Pedido:</span>
                            <span id="view-data" class="ml-1"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Status:</span>
                            <span id="view-status" class="ml-1"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Tipo de Pagamento:</span>
                            <span id="view-pagamento" class="ml-1"></span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Cliente & Vendedor</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Cliente:</span>
                            <span id="view-cliente" class="ml-1"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Vendedor:</span>
                            <span id="view-vendedor" class="ml-1"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Data de Expedição:</span>
                            <span id="view-expedicao" class="ml-1">-</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <h4 class="font-semibold text-gray-700 mt-6 mb-2">Itens do Pedido</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Item</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produto</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Preço Unit.</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Qtde</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Qtde Expedida</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="view-itens" class="bg-white divide-y divide-gray-200 text-sm">
                        <!-- Itens serão inseridos via JavaScript -->
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-right font-bold">Total:</td>
                            <td id="view-total" class="px-4 py-2 text-right font-bold"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="fixed inset-0 bg-black bg-opacity-30 hidden flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-full">
            <svg class="animate-spin h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    @vite(['resources/js/vendas-list.js'])
</x-app-layout>