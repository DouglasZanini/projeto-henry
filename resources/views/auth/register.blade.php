<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-xl rounded-xl p-8 border border-purple-200">
        <h2 class="text-3xl font-semibold text-purple-900 mb-6 text-center">Criar Conta</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-4">
                <x-input-label for="name" value="Nome" />
                <x-text-input id="name" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
            </div>

            <!-- Senha -->
            <div class="mb-4">
                <x-input-label for="password" value="Senha" />
                <x-text-input id="password" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
            </div>

            <!-- Confirmar Senha -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" value="Confirmar Senha" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-purple-700 hover:underline" href="{{ route('login') }}">
                    Já tem uma conta?
                </a>

                <x-primary-button class="bg-purple-700 hover:bg-purple-800 text-white px-5 py-2 rounded-md transition">
                    Registrar
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
