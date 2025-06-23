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

        @if (session('success'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('success') }}
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
                                <button class="text-blue-600 hover:underline edit-cliente" data-id="{{ $cliente->id }}">Editar</button>
                                <form method="POST" action="{{ route('clientes.destroy', $cliente->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline ml-2">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>