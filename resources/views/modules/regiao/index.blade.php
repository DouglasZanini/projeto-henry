<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Regiões
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="flex justify-end  mb-6 gap-4">
               <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-xl font-semibold text-sm text-purple-700 uppercase tracking-wide hover:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    ← Dashboard
                </a>
            <!-- Botão para abrir modal -->
            <button onclick="document.getElementById('modal').classList.remove('hidden')"
                    class="inline-flex items-center px-5 py-2.5 bg-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                Nova Região
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <!-- Tabela -->
        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-purple-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nome</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach($regioes as $regiao)
                        <tr class="border-t hover:bg-purple-50 transition">
                            <td class="px-4 py-2">{{ $regiao->id }}</td>
                            <td class="px-4 py-2">{{ $regiao->nome }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
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
</x-app-layout>
