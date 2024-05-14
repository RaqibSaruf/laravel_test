<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ ucFirst(array_flip(config('common.transactionType'))[$transactionType] ?? '') }}
      </h2>
      <div class="text-sm font-bold">Current Balance: {{ $currentBalance }}</div>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <form method="POST" action="{{ route('transaction.store') }}">
            @csrf
            <input type="hidden" name="transaction_type" value="{{ $transactionType }}" />

            <!-- Name -->
            <div>
              <x-input-label for="amount" :value="__('Amount')" />
              <x-number-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')"
                required autofocus />
              <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ms-4">
                {{ __('Save') }}
              </x-primary-button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>