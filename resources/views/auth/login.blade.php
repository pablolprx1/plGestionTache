<x-guest-layout>
    <!-- Carte de connexion -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text">plGestionTâche</h1>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Identifiant -->
        <div>
            <x-input-label for="login" :value="__('Identifiant')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Se souvenir de moi -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <!-- Boutons -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Lien vers l'inscription -->
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
            {{ __("Vous n'avez pas de compte ?") }}
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900 underline">
                {{ __('Inscrivez-vous ici') }}
            </a>
        </p>
    </div>
</x-guest-layout>
