<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Departamentos') }}
            </h2>
            <button type="button" id="btn-criar-departamento"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Novo Departamento') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Loading indicator -->
            <div id="loading" class="hidden w-full flex justify-center my-4">
                <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
            </div>

            <!-- Alert messages -->
            <div id="alert-container" class="mb-4 hidden">
                <div class="px-4 py-3 rounded relative" role="alert" id="alert-content">
                    <!-- Alert content will be injected here -->
                </div>
            </div>

            <!-- Main content container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="departamentos-container">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nome</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Localização</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="departamentos-table-body">
                                    <!-- Os dados serão carregados aqui via JS -->
                                    @foreach ($departamentos as $departamento)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $departamento->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $departamento->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $departamento->localizacao }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3 view-departamento"
                                                    data-id="{{ $departamento->id }}">Visualizar</button>
                                                <button type="button"
                                                    class="text-blue-600 hover:text-blue-900 mr-3 edit-departamento"
                                                    data-id="{{ $departamento->id }}">Editar</button>
                                                <button type="button"
                                                    class="text-red-600 hover:text-red-900 delete-departamento"
                                                    data-id="{{ $departamento->id }}">Excluir</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para criar/editar departamento -->
    <div id="departamento-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
        aria-modal="true">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Departamento</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="departamento-form">
                        <input type="hidden" id="departamento-id">
                        <div class="mb-4">
                            <label for="nome" class="block text-sm font-medium text-gray-700 text-left">Nome</label>
                            <input type="text" name="nome" id="nome"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="localizacao"
                                class="block text-sm font-medium text-gray-700 text-left">Localização</label>
                            <input type="text" name="localizacao" id="localizacao"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="button" id="save-departamento"
                                class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">Salvar</button>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="button" id="close-modal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar departamento -->
    <div id="view-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detalhes do Departamento</h3>
                <div class="mt-2 px-7 py-3">
                    <div class="mb-4 text-left">
                        <p class="text-sm text-gray-500">ID:</p>
                        <p class="font-medium" id="view-id"></p>
                    </div>
                    <div class="mb-4 text-left">
                        <p class="text-sm text-gray-500">Nome:</p>
                        <p class="font-medium" id="view-nome"></p>
                    </div>
                    <div class="mb-4 text-left">
                        <p class="text-sm text-gray-500">Localização:</p>
                        <p class="font-medium" id="view-localizacao"></p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="button" id="close-view-modal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação para excluir -->
    <div id="confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-red-500 mx-auto" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Confirmar exclusão</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Tem certeza que deseja excluir este departamento? Esta ação não pode ser desfeita.
                    </p>
                    <input type="hidden" id="delete-id">
                    <div class="items-center px-4 py-3 mt-3">
                        <button type="button" id="confirm-delete"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Excluir</button>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="button" id="cancel-delete"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/departamento.js'])
    @endpush
</x-app-layout>
