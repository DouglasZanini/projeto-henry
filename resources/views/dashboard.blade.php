<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-purple-800 leading-tight">
            Painel de Controle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-6 text-gray-800">Módulos do Sistema</h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                        <!-- Departamentos -->
                        <a href="{{ route('departamentos.index') }}"
                           class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7 text-purple-600 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium text-gray-800">Departamentos</span>
                        </a>

                        <a href="{{ route('regiao.index') }}">
                        <div class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7 text-green-500 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Regiões</span>
                        </div>
                        </a>
                        
                        <a href="{{ route('empregados.index') }}" class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7 text-purple-500 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Empregados</span>
                        </a>

                            <a href="{{ route('vendas.index') }}"
                            class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-purple-600 mb-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Vendas</span>
                            </a>

                            <a href="{{ route('produtos.index') }}"
                            class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-purple-600 mb-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Produtos</span>
                            </a>

                            <a href="{{ route('clientes.index') }}"
                            class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-purple-600 mb-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Clientes</span>
                            </a>

                            <a href="{{ route('vendas.index') }}"
                            class="border border-gray-300 rounded-lg p-4 hover:bg-purple-50 transition-all duration-200 flex flex-col items-center text-center shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-purple-600 mb-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800"></span>
                            </a>
                        
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
