<div class="ip-workspace"
    x-data="{
        open: false, tab: 'payments', invoiceOpen: false, chooseInvoice: false, returnFocus: null,
        showDrawer() {
            if (!this.open) this.returnFocus = document.activeElement;
            this.tab = 'payments';
            this.chooseInvoice = false;
            this.$nextTick(() => {
                this.$refs.drawerBody.scrollTop = 0;
                this.open = true;
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) this.$nextTick(() => this.$refs.drawerClose.focus({ preventScroll: true }));
            });
        },
        closeDrawer() {
            this.open = false;
            this.$nextTick(() => this.returnFocus?.focus({ preventScroll: true }));
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) this.$wire.set('drawerOpen', false);
        },
        finishDrawerTransition(event) {
            if (event.propertyName !== 'transform') return;
            if (this.open) this.$refs.drawerClose.focus({ preventScroll: true });
            else this.$wire.set('drawerOpen', false);
        }
    }"
    @customer-payments-opened.window="showDrawer()"
    @payment-invoice-ready.window="invoiceOpen = true"
    @invoice-slider-closed.window="invoiceOpen = false; if (open) $nextTick(() => $refs.drawerClose?.focus({ preventScroll: true }))"
    @keydown.escape.window="if (open && !invoiceOpen) closeDrawer()">
    @push('main_header')
    <link rel="stylesheet" href="{{ asset('css/invoice-payments.css') }}?v={{ filemtime(public_path('css/invoice-payments.css')) }}">
    @endpush
    <livewire:invoice-item />
    <header class="ip-heading">
        <div><span class="ip-eyebrow">Payment workspace</span><h1>Invoice payments</h1><p>Every customer. Every payment. A clear view of what remains.</p></div>
        <span class="ip-scope">All-time balances</span>
    </header>
    <div class="ip-metrics">
        <div class="ip-metric"><span>Total invoiced</span><strong>&dollar;{{ number_format($summary->invoiced, 2) }}</strong><small>Invoice totals for matching customers</small></div>
        <div class="ip-metric"><span>Payments received</span><strong>&dollar;{{ number_format($summary->received, 2) }}</strong><small>All recorded payments</small></div>
        <div class="ip-metric ip-metric-accent"><span>Outstanding</span><strong>&dollar;{{ number_format($summary->outstanding, 2) }}</strong><small>Remaining on active invoices</small></div>
        <div class="ip-metric"><span>Customers</span><strong>{{ number_format($summary->companies) }}</strong><small>Matching your filters</small></div>
    </div>
    <section class="ip-card">
        <div class="ip-toolbar">
            <label class="ip-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4.5 4.5"/></svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search customer, invoice or payment reference" aria-label="Search customers, invoices or payment references">
            </label>
            <div class="ip-segments" aria-label="Customer balance filters">
                <button type="button" wire:click="$set('statusFilter', 'all')" class="{{ $statusFilter === 'all' ? 'is-active' : '' }}" aria-pressed="{{ $statusFilter === 'all' ? 'true' : 'false' }}">All customers</button>
                <button type="button" wire:click="$set('statusFilter', 'outstanding')" class="{{ $statusFilter === 'outstanding' ? 'is-active' : '' }}" aria-pressed="{{ $statusFilter === 'outstanding' ? 'true' : 'false' }}">Outstanding</button>
                <button type="button" wire:click="$set('statusFilter', 'paid')" class="{{ $statusFilter === 'paid' ? 'is-active' : '' }}" aria-pressed="{{ $statusFilter === 'paid' ? 'true' : 'false' }}">Settled</button>
            </div>
        </div>
        <div class="ip-list-caption"><span>{{ $customers->total() }} {{ \Illuminate\Support\Str::plural('customer', $customers->total()) }} · Latest payments first</span><span>Select a customer to view all payments <span aria-hidden="true">↗</span></span></div>
        <div class="ip-table-scroll" wire:loading.class="ip-updating" wire:target="search,statusFilter">
            <table class="ip-table">
                <thead><tr>
                    @foreach (['company' => 'Customer', 'invoiced' => 'Total invoiced', 'received' => 'Received', 'profit' => 'Profit', 'outstanding' => 'Outstanding', 'last_payment' => 'Last payment'] as $column => $label)
                        <th scope="col" class="{{ in_array($column, ['invoiced', 'received', 'profit', 'outstanding']) ? 'ip-money' : '' }}" @if($column === 'last_payment') aria-sort="descending" @endif @if($column === 'profit') title="Receipts minus item selling totals for active invoices only. Excludes memos, transferred and returned invoices. Actual product costs are not recorded, so this is not accounting profit." @endif>
                            {{ $label }} @if($column === 'last_payment')<span aria-hidden="true" class="ip-sort">↓</span>@endif
                        </th>
                    @endforeach
                    <th scope="col">Status</th>
                </tr></thead>
                <tbody>
                    @forelse ($customers as $row)
                        <tr wire:key="customer-{{ $row->id }}" class="{{ $customerId === $row->id && $drawerOpen ? 'is-selected' : '' }}">
                            <td><button type="button" class="ip-customer" wire:click="getPayment({{ $row->id }})" wire:loading.attr="disabled" wire:target="getPayment">
                                <span class="ip-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($row->company ?: 'C', 0, 1)) }}</span>
                                <span><strong>{{ $row->company ?: 'Customer #'.$row->id }}</strong><small>{{ $row->invoice_count }} {{ $row->invoice_count == 1 ? 'invoice / order' : 'invoices / orders' }} · {{ $row->payment_count }} {{ \Illuminate\Support\Str::plural('payment', $row->payment_count) }}</small></span>
                            </button></td>
                            <td class="ip-money">&dollar;{{ number_format($row->invoiced, 2) }}</td>
                            <td class="ip-money">&dollar;{{ number_format($row->received, 2) }}</td>
                            <td class="ip-money {{ $row->profit < 0 ? 'ip-profit-negative' : ($row->profit > 0 ? 'ip-profit-positive' : 'ip-muted') }}">{{ $row->profit < 0 ? '−' : '' }}&dollar;{{ number_format(abs($row->profit), 2) }}</td>
                            <td class="ip-money {{ $row->outstanding > 0 ? 'ip-amount-due' : 'ip-muted' }}">&dollar;{{ number_format($row->outstanding, 2) }}</td>
                            <td class="ip-nowrap">{{ $row->last_payment ? \Carbon\Carbon::parse($row->last_payment)->format('M d, Y') : 'No payments yet' }}</td>
                            <td>
                                @if ($row->outstanding > 0)<span class="ip-badge ip-badge-amber">{{ $row->received > 0 ? 'Partial' : 'Unpaid' }}</span>
                                @else<span class="ip-badge ip-badge-green">Settled</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="ip-empty"><strong>No customers found</strong><p>Try another company name, invoice number or reference.</p>@if($search || $statusFilter !== 'all')<button class="ip-button" type="button" wire:click="clearCustomerFilters">Clear filters</button>@endif</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('livewire.partials.payment-pagination', ['paginator' => $customers])
    </section>
    <p class="ip-footnote">Outstanding excludes memos, transferred and returned invoices. Overpayments on one invoice do not offset another invoice's balance.</p>
    <p class="ip-footnote">Profit shows receipts minus item selling totals for active invoices only. It is not the amount owed or accounting profit; actual product costs are not recorded.</p>
    <div wire:loading.delay wire:target="getPayment" class="ip-toast" role="status">Opening customer payments…</div>

    <div wire:ignore.self id="slideover-payment-container" class="ip-overlay" x-cloak :class="{ 'is-open': open }" :aria-hidden="!open">
        <div class="ip-backdrop" @click="if (!invoiceOpen) closeDrawer()" aria-hidden="true"></div>
        <section wire:ignore.self class="ip-drawer" role="dialog" aria-modal="true" aria-labelledby="ip-customer-title"
            x-trap.inert.noscroll.noautofocus.noreturn="open && !invoiceOpen"
            @transitionend.self="finishDrawerTransition($event)">
            <header class="ip-drawer-header">
                <div class="ip-drawer-title">
                    <div><span class="ip-eyebrow">Payments / Customer history</span><h2 id="ip-customer-title">{{ $customer?->company ?: 'Customer payments' }}</h2><p>Every payment across all invoices.</p></div>
                    <button type="button" x-ref="drawerClose" class="ip-icon-button" @click="closeDrawer()" aria-label="Close customer payments">✕</button>
                </div>
                @if ($customerTotals)
                    <div class="ip-metrics ip-drawer-metrics">
                        <div class="ip-metric"><span>Total invoiced</span><strong>&dollar;{{ number_format($customerTotals->invoiced, 2) }}</strong></div>
                        <div class="ip-metric"><span>Total received</span><strong>&dollar;{{ number_format($customerTotals->received, 2) }}</strong></div>
                        <div class="ip-metric ip-metric-accent"><span>Outstanding</span><strong>&dollar;{{ number_format($customerTotals->outstanding, 2) }}</strong></div>
                    </div>
                    @if ($customerTotals->overpaid > 0)<p class="ip-footnote">Overpaid invoices: &dollar;{{ number_format($customerTotals->overpaid, 2) }}. Shown separately from outstanding balances.</p>@endif
                @endif
                <div class="ip-tabs" role="tablist" aria-label="Customer payment views">
                    <button id="ip-payments-tab" type="button" role="tab" :aria-selected="tab === 'payments'" :tabindex="tab === 'payments' ? 0 : -1" aria-controls="ip-payments-panel" :class="{ 'is-active': tab === 'payments' }" @click="tab = 'payments'" @keydown.arrow-right.prevent="tab = 'invoices'; $refs.invoicesTab.focus()">All payments ({{ $customerTotals->payment_count ?? 0 }})</button>
                    <button id="ip-invoices-tab" x-ref="invoicesTab" type="button" role="tab" :aria-selected="tab === 'invoices'" :tabindex="tab === 'invoices' ? 0 : -1" aria-controls="ip-invoices-panel" :class="{ 'is-active': tab === 'invoices' }" @click="tab = 'invoices'" @keydown.arrow-left.prevent="tab = 'payments'; document.getElementById('ip-payments-tab').focus()">Invoices ({{ $customerTotals->invoice_count ?? 0 }})</button>
                </div>
            </header>
            <div class="ip-drawer-body" x-ref="drawerBody">
                @if ($customer && $payments)
                    <div id="ip-payments-panel" role="tabpanel" aria-labelledby="ip-payments-tab" x-show="tab === 'payments'">
                        <div class="ip-payment-filters">
                            <label class="ip-field ip-filter-search"><span>Find a payment</span><input type="search" wire:model.live.debounce.300ms="paymentSearch" placeholder="Invoice or reference"></label>
                            <label class="ip-field"><span>Invoice</span><select wire:model.live="invoiceFilter"><option value="">All invoices</option>@foreach($invoiceOptions as $invoice)<option value="{{ $invoice->id }}">#{{ $invoice->id }}{{ $invoice->method !== 'Invoice' ? ' · '.$invoice->method : '' }}</option>@endforeach</select></label>
                            <label class="ip-field"><span>From</span><input type="date" wire:model.live="dateFrom" aria-label="Payments from date"></label>
                            <label class="ip-field"><span>To</span><input type="date" wire:model.live="dateTo" aria-label="Payments to date"></label>
                        </div>
                        @error('dateFrom')<p class="ip-error" role="alert">{{ $message }}</p>@enderror
                        @error('dateTo')<p class="ip-error" role="alert">{{ $message }}</p>@enderror
                        <div class="ip-list-caption">
                            <span>{{ $payments->total() }} {{ \Illuminate\Support\Str::plural('payment', $payments->total()) }} · {{ $paymentSearch || $invoiceFilter || $dateFrom || $dateTo ? 'Filtered results' : 'All time' }}</span>
                            <div class="ip-inline-actions">
                                @if ($paymentSearch || $invoiceFilter || $dateFrom || $dateTo)<button type="button" class="ip-link" wire:click="clearPaymentFilters">Reset filters</button>@endif
                                <button type="button" class="ip-button ip-button-small" wire:click="exportPayments" wire:loading.attr="disabled" wire:target="exportPayments" @disabled($payments->total() === 0)>Export CSV</button>
                            </div>
                        </div>
                        <div class="ip-table-scroll ip-ledger" wire:loading.class="ip-updating" wire:target="paymentSearch,invoiceFilter,dateFrom,dateTo">
                            <table class="ip-table">
                                <thead><tr><th scope="col">Payment date</th><th scope="col">Invoice</th><th scope="col">Reference</th><th scope="col" class="ip-money">Amount received</th></tr></thead>
                                <tbody>
                                    @forelse ($payments as $payment)
                                        <tr wire:key="payment-{{ $payment->id }}">
                                            <td class="ip-nowrap">{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') : '—' }}</td>
                                            <td><button type="button" class="ip-link ip-invoice-link" wire:click="openInvoice({{ $payment->order_id }})" wire:loading.attr="disabled" wire:target="openInvoice">#{{ $payment->order_id }}</button></td>
                                            <td class="ip-reference">{{ $payment->ref ?: '—' }}</td>
                                            <td class="ip-money">&dollar;{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4"><div class="ip-empty"><strong>{{ $customerTotals?->payment_count ? 'No payments match these filters' : 'No payments recorded yet' }}</strong><p>{{ $customerTotals?->payment_count ? 'Adjust your search, invoice or date range.' : 'Open an outstanding invoice below to record its first payment.' }}</p></div></td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot><tr><th scope="row" colspan="3">Total received · {{ $paymentSearch || $invoiceFilter || $dateFrom || $dateTo ? 'All filtered results' : 'All dates' }}</th><td class="ip-money">&dollar;{{ number_format($filteredReceived, 2) }}</td></tr></tfoot>
                            </table>
                        </div>
                        @include('livewire.partials.payment-pagination', ['paginator' => $payments])
                        <p class="ip-footnote">Select an invoice number to open its details. Totals include every matching payment, across all pages.</p>
                    </div>
                    <div id="ip-invoices-panel" role="tabpanel" aria-labelledby="ip-invoices-tab" x-show="tab === 'invoices'">
                        <div class="ip-list-caption"><span>All invoices and orders with payment history</span><span>All time</span></div>
                        <div class="ip-table-scroll ip-ledger">
                            <table class="ip-table">
                                <thead><tr><th scope="col">Invoice</th><th scope="col" class="ip-money">Total</th><th scope="col" class="ip-money">Received</th><th scope="col" class="ip-money">Outstanding</th><th scope="col">Status</th><th scope="col"><span class="ip-sr-only">Action</span></th></tr></thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr wire:key="invoice-{{ $invoice->id }}">
                                            <td><button type="button" class="ip-link ip-invoice-link" wire:click="openInvoice({{ $invoice->id }})" wire:loading.attr="disabled" wire:target="openInvoice">#{{ $invoice->id }}</button>@if($invoice->method !== 'Invoice')<small class="ip-cell-note">{{ $invoice->method }}</small>@endif</td>
                                            <td class="ip-money">&dollar;{{ number_format($invoice->total, 2) }}</td>
                                            <td class="ip-money">&dollar;{{ number_format($invoice->received, 2) }}</td>
                                            <td class="ip-money">&dollar;{{ number_format($invoice->outstanding, 2) }}</td>
                                            <td>
                                                @if(in_array((int) $invoice->status, [2, 3]))<span class="ip-badge">{{ (int) $invoice->status === 2 ? 'Transferred' : 'Returned' }}</span>
                                                @elseif($invoice->method !== 'Invoice')<span class="ip-badge">{{ $invoice->method }}</span>
                                                @elseif($invoice->overpaid > 0)<span class="ip-badge ip-badge-green">Overpaid</span><small class="ip-cell-note">&dollar;{{ number_format($invoice->overpaid, 2) }}</small>
                                                @elseif($invoice->outstanding > 0)<span class="ip-badge ip-badge-amber">{{ $invoice->received > 0 ? 'Partial' : 'Unpaid' }}</span>
                                                @else<span class="ip-badge ip-badge-green">Paid</span>@endif
                                            </td>
                                            <td>@if($invoice->outstanding > 0)<button type="button" class="ip-link ip-nowrap" wire:click="openInvoice({{ $invoice->id }}, true)" wire:loading.attr="disabled" wire:target="openInvoice">Record payment</button>@endif</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6"><div class="ip-empty">No invoices found.</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @include('livewire.partials.payment-pagination', ['paginator' => $invoices])
                        <p class="ip-footnote">Outstanding excludes memos, transferred and returned invoices.</p>
                    </div>
                    @if($outstandingInvoices->isNotEmpty())
                        <section class="ip-balances" x-show="tab === 'payments' || chooseInvoice" x-ref="balances">
                            <div class="ip-balance-heading"><h3>Remaining balances</h3><span>{{ $outstandingInvoices->count() }} {{ \Illuminate\Support\Str::plural('invoice', $outstandingInvoices->count()) }}</span></div>
                            <p x-show="chooseInvoice" class="ip-footnote" role="status">Choose the invoice you want to apply a payment to.</p>
                            @foreach($outstandingInvoices as $invoice)
                                <div class="ip-balance-row" wire:key="balance-{{ $invoice->id }}">
                                    <div><button type="button" class="ip-link" wire:click="openInvoice({{ $invoice->id }})">Invoice #{{ $invoice->id }}</button><small>Total &dollar;{{ number_format($invoice->total, 2) }} · Received &dollar;{{ number_format($invoice->received, 2) }}</small></div>
                                    <strong class="ip-money">&dollar;{{ number_format($invoice->outstanding, 2) }}</strong>
                                    <button type="button" class="ip-button ip-button-small" wire:click="openInvoice({{ $invoice->id }}, true)" wire:loading.attr="disabled" wire:target="openInvoice">Record payment</button>
                                </div>
                            @endforeach
                        </section>
                    @else
                        <div class="ip-settled" x-show="tab === 'payments'"><span aria-hidden="true">✓</span> No outstanding invoice balances.</div>
                    @endif
                @elseif($drawerOpen)
                    <div class="ip-empty">This customer is no longer available.</div>
                @endif
            </div>
            <footer class="ip-drawer-footer">
                <button type="button" class="ip-button" @click="closeDrawer()">Close</button>
                @if($outstandingInvoices->count() === 1)
                    <button type="button" class="ip-button ip-button-primary" wire:click="openInvoice({{ $outstandingInvoices->first()->id }}, true)" wire:loading.attr="disabled" wire:target="openInvoice">Record payment <span aria-hidden="true">→</span></button>
                @elseif($outstandingInvoices->count() > 1)
                    <button type="button" class="ip-button ip-button-primary" @click="chooseInvoice = true; $nextTick(() => $refs.balances.scrollIntoView({ behavior: 'smooth', block: 'start' }))">Record payment <span aria-hidden="true">→</span></button>
                @else
                    <span class="ip-muted">All active invoice balances are settled</span>
                @endif
            </footer>
        </section>
    </div>
</div>

