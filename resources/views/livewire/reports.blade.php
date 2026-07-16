<div class="space-y-5">
    <div wire:loading.delay class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/35" wire:target="setTab,search,dateFrom,dateTo,customerId,resetFilters,sort,gotoPage,nextPage,previousPage">
        <div class="rounded-full bg-white p-3 shadow-xl dark:bg-gray-800">
            <svg class="h-7 w-7 animate-spin text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
            </svg>
        </div>
    </div>

    <section class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm dark:bg-gray-800 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Outstanding memos and unpaid invoices</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Only records with a remaining balance are included.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('reports.print-unpaid', ['isMemo' => 1]) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829a3 3 0 0 0-3 3v1.5a3 3 0 0 0 3 3h10.56a3 3 0 0 0 3-3v-1.5a3 3 0 0 0-3-3M6.72 17.579h10.56M7.5 13.829V3.75h9v10.079M8.25 6.75h7.5" />
                </svg>
                Print On Memo
            </a>
            <a href="{{ route('reports.print-unpaid', ['isMemo' => 0]) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-amber-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829a3 3 0 0 0-3 3v1.5a3 3 0 0 0 3 3h10.56a3 3 0 0 0 3-3v-1.5a3 3 0 0 0-3-3M6.72 17.579h10.56M7.5 13.829V3.75h9v10.079M8.25 6.75h7.5" />
                </svg>
                Print Invoices
            </a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-amber-100 p-3 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                    <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 12.75h7.5m-7.5 3h4.5m-5.25 3h9A2.25 2.25 0 0 0 18.75 18.75V9.108a2.25 2.25 0 0 0-.659-1.591l-4.608-4.608a2.25 2.25 0 0 0-1.591-.659H7.5A2.25 2.25 0 0 0 5.25 4.5v14.25A2.25 2.25 0 0 0 7.5 21Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">On Memo Balance</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($memoBalance, 2) }}</p>
                </div>
            </div>
        </article>

        <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-blue-100 p-3 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                    <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25 10.5 15.75 13.5 12.75m4.5-9H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unpaid Invoice Balance</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($invoiceBalance, 2) }}</p>
                </div>
            </div>
        </article>

        <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m3-9.75H10.5a2.25 2.25 0 0 0 0 4.5h3a2.25 2.25 0 0 1 0 4.5H9M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Outstanding</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($memoBalance + $invoiceBalance, 2) }}</p>
                </div>
            </div>
        </article>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-5 dark:border-gray-700">
            <nav class="flex gap-8" aria-label="Report type">
                <button type="button" wire:click="setTab('memo')"
                    class="border-b-2 px-1 py-4 text-sm font-semibold transition {{ $activeTab === 'memo' ? 'border-amber-500 text-amber-700 dark:text-amber-300' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                    On Memo ({{ $memoCount }})
                </button>
                <button type="button" wire:click="setTab('invoice')"
                    class="border-b-2 px-1 py-4 text-sm font-semibold transition {{ $activeTab === 'invoice' ? 'border-amber-500 text-amber-700 dark:text-amber-300' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                    Unpaid Invoices ({{ $invoiceCount }})
                </button>
            </nav>
        </div>

        <div class="border-b border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[minmax(240px,1.3fr)_170px_170px_minmax(210px,1fr)_auto]">
                <label class="relative block">
                    <span class="sr-only">Search</span>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                    </svg>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search customer or invoice…"
                        class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                </label>

                <label>
                    <span class="sr-only">From date</span>
                    <input type="date" wire:model.live="dateFrom" title="From date"
                        class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </label>

                <label>
                    <span class="sr-only">To date</span>
                    <input type="date" wire:model.live="dateTo" title="To date"
                        class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </label>

                <label>
                    <span class="sr-only">Customer</span>
                    <select wire:model.live="customerId"
                        class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-amber-500 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">All Customers</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->company ?: trim($customer->firstname.' '.$customer->lastname) }}</option>
                        @endforeach
                    </select>
                </label>

                <button type="button" wire:click="resetFilters"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 1 0 2.197-5.303L4.5 9m0-4.5V9H9" />
                    </svg>
                    Reset
                </button>
            </div>

            <div class="mt-3 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25 12 10.5m0 0 .75.75M12 10.5v4.125m9-2.625a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Only outstanding balances are shown.
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="border-b border-gray-200 bg-white text-xs uppercase text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        @foreach ([
                            'id' => $activeTab === 'memo' ? 'Memo #' : 'Invoice #',
                            'customer' => 'Customer',
                            'created_at' => 'Date',
                        ] as $column => $label)
                            <th scope="col" class="px-5 py-3.5">
                                <button type="button" wire:click="sort('{{ $column }}')" class="inline-flex items-center gap-1 font-semibold hover:text-amber-700">
                                    {{ $label }}
                                    <span class="text-[10px]">{{ $sortBy === $column ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                </button>
                            </th>
                        @endforeach
                        <th scope="col" class="px-5 py-3.5 font-semibold">Due Date</th>
                        <th scope="col" class="px-5 py-3.5 text-center font-semibold">Items</th>
                        <th scope="col" class="px-5 py-3.5 text-right">
                            <button type="button" wire:click="sort('total')" class="inline-flex items-center gap-1 font-semibold hover:text-amber-700">
                                Original Amount
                                <span class="text-[10px]">{{ $sortBy === 'total' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th scope="col" class="px-5 py-3.5 text-right">
                            <button type="button" wire:click="sort('balance')" class="inline-flex items-center gap-1 font-semibold hover:text-amber-700">
                                Balance
                                <span class="text-[10px]">{{ $sortBy === 'balance' ? ($sortDirection === 'asc' ? '▲' : '▼') : '↕' }}</span>
                            </button>
                        </th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($orders as $order)
                        @php($dueDate = $this->dueDate($order))
                        @php($overdue = $this->isOverdue($order))
                        <tr wire:key="report-order-{{ $order->id }}" class="bg-white hover:bg-amber-50/50 dark:bg-gray-800 dark:hover:bg-gray-700/70">
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-amber-700 dark:text-amber-300">
                                {{ $activeTab === 'memo' ? 'MEMO-' : 'INV-' }}{{ $order->id }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $this->customerName($order) }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $order->created_at->format('M j, Y') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 {{ $overdue ? 'font-medium text-red-600 dark:text-red-400' : '' }}">
                                {{ $dueDate?->format('M j, Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-center">{{ $order->products_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">${{ number_format($order->total, 2) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">${{ number_format($order->balance, 2) }}</td>
                            <td class="px-5 py-4">
                                @if ($overdue)
                                    <span class="inline-flex rounded-md border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">Overdue</span>
                                @elseif ($activeTab === 'memo')
                                    <span class="inline-flex rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">On Memo</span>
                                @else
                                    <span class="inline-flex rounded-md border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300">Unpaid</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="/admin/orders/{{ $order->id }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">View</a>
                                    <a href="/admin/orders/{{ $order->id }}/print" target="_blank" title="Print {{ $activeTab === 'memo' ? 'memo' : 'invoice' }}"
                                        class="rounded-lg border border-gray-300 p-1.5 text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829a3 3 0 0 0-3 3v1.5a3 3 0 0 0 3 3h10.56a3 3 0 0 0 3-3v-1.5a3 3 0 0 0-3-3M6.72 17.579h10.56M7.5 13.829V3.75h9v10.079M8.25 6.75h7.5" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <p class="font-medium text-gray-700 dark:text-gray-200">No outstanding {{ $activeTab === 'memo' ? 'memos' : 'invoices' }} found</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try resetting the filters or changing the selected date range.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        @else
            <div class="border-t border-gray-200 px-5 py-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                Showing {{ $orders->count() }} of {{ $orders->total() }} records
            </div>
        @endif
    </section>
</div>
