<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="tu-correo@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class=" justify-center bg-custom-gradient-2 ms-3 ml-0 w-full text-center ms-0 hover:text-blue-200">
                {{ __('Restablecer contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
