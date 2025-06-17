<section>
    <header>
        <h2 class="text-lg font-semibold text-purple-900">
            Atualizar Senha
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Garanta que sua conta esteja protegida com uma senha forte e segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Senha atual -->
        <div>
            <x-input-label for="update_password_current_password" value="Senha atual" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600" />
        </div>

        <!-- Nova senha -->
        <div>
            <x-input-label for="update_password_password" value="Nova senha" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Confirmação da nova senha -->
        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmar nova senha" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <!-- Botão -->
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-md">
                Salvar
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >
                    Senha atualizada com sucesso.
                </p>
            @endif
        </div>
    </form>
</section>
