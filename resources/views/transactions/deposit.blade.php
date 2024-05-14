<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Deposits') }}
      </h2>
      <a href="{{ route('transaction.deposit.add') }}">Add Deposit</a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">


          <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="px-6 py-3">
                    Transaction Type
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Amount
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Date
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach($transactions as $transaction)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ ucFirst(array_flip(config('common.transactionType'))[$transaction->transaction_type] ?? '') }}
                  </th>
                  <td class="px-6 py-4">
                    {{ $transaction->amount }}
                  </td>
                  <td class="px-6 py-4">
                    {{ $transaction->date }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mt-5">
              {{ $transactions->links() }}
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>