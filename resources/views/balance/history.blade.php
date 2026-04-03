<x-layouts::app :title="__('Transaction History')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">{{ __('Transaction History') }}</flux:heading>
                <flux:text class="mt-1">{{ __('Your wallet activity and balance changes.') }}</flux:text>
            </div>
            <div class="text-right">
                <flux:text class="text-sm">{{ __('Current Balance') }}</flux:text>
                <div class="text-2xl font-bold text-zinc-900 dark:text-white" data-test="history-balance">
                    ${{ number_format((float) auth()->user()->balanceFloat, 2) }}
                </div>
            </div>
        </div>

        @if ($transactions->isEmpty())
            <div class="rounded-xl border border-neutral-200 bg-white p-12 text-center dark:border-neutral-700 dark:bg-zinc-900" data-test="empty-state">
                <flux:icon icon="wallet" class="mx-auto size-12 text-neutral-400" />
                <flux:heading size="lg" class="mt-4">{{ __('No transactions yet') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Your transaction history will appear here once you have activity.') }}</flux:text>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700" data-test="transactions-table">
                    <thead class="bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Description') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-zinc-900">
                        @foreach ($transactions as $transaction)
                            @php
                                $amount = $transaction->amount / (10 ** $transaction->wallet->decimal_places);
                                $isDeposit = $transaction->type === 'deposit';
                            @endphp
                            <tr data-test="transaction-row">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $isDeposit,
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => ! $isDeposit,
                                    ])>
                                        {{ $isDeposit ? __('Deposit') : __('Withdrawal') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $transaction->meta['reason'] ?? '—' }}
                                </td>
                                <td @class([
                                    'whitespace-nowrap px-6 py-4 text-right text-sm font-medium',
                                    'text-green-600 dark:text-green-400' => $isDeposit,
                                    'text-red-600 dark:text-red-400' => ! $isDeposit,
                                ])>
                                    {{ $isDeposit ? '+' : '-' }}${{ number_format(abs($amount), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-2">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
