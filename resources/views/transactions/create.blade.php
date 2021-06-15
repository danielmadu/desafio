<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('transactions.save') }}">
                @csrf
                <!-- Payee -->
                <div>
                    <x-label for="payee" :value="__('To')" />

                    <x-input id="payee" class="block w-full mt-1" type="text" name="payee" :value="old('payee')" required autofocus />
                </div>

                <!-- Value -->
                <div>
                    <x-label for="value" :value="__('Value')" />

                    <x-input id="value" class="block w-full mt-1" type="text" name="value" :value="old('value')" required autofocus />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ml-4">
                        {{ __('Send') }}
                    </x-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
