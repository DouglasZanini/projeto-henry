<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Produtos
        </h2>
    </x-slot>
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-end items-center mb-6">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                ← Dashboard
            </a>
            <button onclick="openModal()" class="bg-purple-700 text-white px-4 py-2 rounded-xl shadow hover:bg-purple-800">Novo Produto</button>
         </div>  
    </div>
  

    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <thead class="bg-purple-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs  font-bold text-purple-800 uppercase tracking-wide">Nome</th>
                <th class="px-6 py-4 text-left text-xs  font-bold text-purple-800 uppercase tracking-wide">Descrição</th>
                <th class="px-6 py-4 text-left text-xs  font-bold text-purple-800 uppercase tracking-wide">Preço</th>
                <th class="px-6 py-4 text-left text-xs  font-bold text-purple-800 uppercase tracking-wide">Unidades</th>
                <th class="px-6 py-4 text-left text-xs  font-bold text-purple-800 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
            @foreach($produtos as $produto)
                <tr class="text-left border-t">
                    <td class="px-4 py-2">{{ $produto->nome }}</td>
                    <td class="px-4 py-2">{{ $produto->descricao_breve }}</td>
                    <td class="px-4 py-2">R$ {{ number_format($produto->preco_sugerido, 2, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $produto->unidades }}</td>
                    <td class="px-4 py-2">
                        <!-- Aqui pode incluir botões de editar/excluir -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal criar -->
    <div id="produto-modal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-lg relative">
            <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600">×</button>
            <h3 class="text-lg font-semibold mb-4 text-purple-700">Cadastrar Produto</h3>
            <form method="POST" action="{{ route('produtos.store') }}">
                @csrf
                <label class="block mb-2">Nome</label>
                <input name="nome" required class="w-full mb-4 border rounded p-2" />

                <label class="block mb-2">Descrição Breve</label>
                <input name="descricao_breve" class="w-full mb-4 border rounded p-2" />

                <label class="block mb-2">Preço Sugerido</label>
                <input name="preco_sugerido" type="number" step="0.01" class="w-full mb-4 border rounded p-2" />

                <label class="block mb-2">Unidades</label>
                <input name="unidades" type="number" class="w-full mb-4 border rounded p-2" />

                <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded-xl hover:bg-purple-800">Salvar</button>
            </form>
        </div>
    </div>
</div>

    <script>
        function openModal() {
            document.getElementById('produto-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('produto-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
