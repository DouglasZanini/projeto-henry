<x-guest-layout>
    <div class="mb-4 text-sm">
        Esqueceu sua senha? Sem problemas. Informe seu endereço de e-mail e enviaremos um link para redefinição de senha, onde você poderá escolher uma nova.
    </div>

    <!-- Status da Sessão -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Campo de E-mail -->
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input 
                id="email" 
                name="email" 
                type="email" 
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-200" 
                :value="old('email')" 
                required 
                autofocus 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Botão -->
        <div class="flex items-center justify-end">
            <x-primary-button class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-lg">
                Enviar link de redefinição
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
