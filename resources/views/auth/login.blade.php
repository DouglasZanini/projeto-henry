<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-xl rounded-xl p-8 border border-purple-200">
        <h2 class="text-3xl font-semibold text-purple-900 mb-6 text-center">Entrar na Conta</h2>

        <!-- Mensagem de Sessão -->
        <x-auth-session-status class="mb-4 text-green-600" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <x-input-label for="email" value="E-mail" />
                <x-text-input id="email" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
            </div>

            <!-- Senha -->
            <div class="mb-4">
                <x-input-label for="password" value="Senha" />
                <x-text-input id="password" class="block mt-1 w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
            </div>

            <!-- Lembrar de mim -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-purple-300 text-purple-600 shadow-sm focus:ring-purple-400" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Lembrar de mim</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-purple-700 hover:underline" href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

            <!-- Botão de login -->
            <div>
                <x-primary-button class=" bg-purple-700 hover:bg-purple-800 text-white px-5 py-2 rounded-md transition">
                    Entrar
                </x-primary-button>
                <a>
                    <p class="mt-4 text-sm text-gray-600">
                        Não tem uma conta? 
                        <a href="{{ route('register') }}" class="text-purple-600 hover:underline">Cadastre-se</a>
                    </p>
                </a>
            </div>
            
        </form>
    </div>
</x-guest-layout>
