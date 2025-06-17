<section>
    <header>
        <h2 class="text-lg font-semibold text-purple-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Atualize suas informações de perfil e endereço de e-mail.
        </p>
    </header>

    <!-- Formulário de reenvio de verificação -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Formulário de atualização -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nome -->
        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full border border-purple-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

            <!-- Verificação de e-mail -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-800">
                    Seu e-mail ainda não foi verificado.
                    <button form="send-verification" class="underline text-purple-700 hover:text-purple-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Clique aqui para reenviar o e-mail de verificação.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Um novo link de verificação foi enviado para o seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Botão de salvar -->
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-md">
                Salvar
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >Salvo com sucesso.</p>
            @endif
        </div>
    </form>
</section>
