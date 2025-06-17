<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-purple-900">
            Excluir Conta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Uma vez que sua conta for excluída, todos os seus dados e recursos serão permanentemente apagados. Antes de continuar, salve todas as informações que deseja manter.
        </p>
    </header>

    <!-- Botão principal de exclusão -->
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
    >
        Excluir Conta
    </x-danger-button>

    <!-- Modal de confirmação -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-red-700">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Após a exclusão, todos os dados serão apagados permanentemente. Digite sua senha abaixo para confirmar.
            </p>

            <!-- Campo de senha -->
            <div class="mt-6">
                <x-input-label for="password" value="Senha" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Senha"
                    class="mt-1 block w-full border border-red-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600" />
            </div>

            <!-- Botões de ação -->
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="text-gray-700 hover:text-purple-700">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    Confirmar Exclusão
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
