<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Payee -->
        <div>
            <x-label for="payee" :value="__('To')" />

            <x-input id="payee" class="block w-full mt-1" type="text" name="payee" :value="old('payee')" required autofocus />
        </div>
    </div>
</x-app-layout>
