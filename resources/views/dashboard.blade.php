<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">{{ __('Módulos do Sistema') }}</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <!-- Módulo de Departamentos -->
                        <a href="{{ route('departamentos.index') }}" class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-all flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium">Departamentos</span>
                        </a>
                        
                        <!-- Módulo de Regiões (futuro) -->
                        <a href="#" class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-all flex flex-col items-center opacity-50 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium">Regiões</span>
                            <span class="text-xs text-gray-500">(Em breve)</span>
                        </a>
                        
                        <!-- Módulo de Empregados (futuro) -->
                        <a href="#" class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-all flex flex-col items-center opacity-50 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium">Empregados</span>
                            <span class="text-xs text-gray-500">(Em breve)</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>