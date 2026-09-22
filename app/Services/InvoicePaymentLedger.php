<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class InvoicePaymentLedger
{
    /** Aggregate payments first, so multiple payments never multiply invoice totals. */
    public function invoices(): Builder
    {
        $payments = DB::table('order_payment')
            ->selectRaw('order_id, SUM(amount) as received, COUNT(*) as payment_count, MAX(created_at) as last_payment')
            ->groupBy('order_id');
        $links = DB::table('customer_order')->select('customer_id', 'order_id')->distinct();
        $balance = 'ROUND(orders.total - COALESCE(payments.received, 0), 2)';
        $collectible = "orders.method = 'Invoice' AND (orders.status IN (0, 1) OR orders.status IS NULL)";

        return DB::table('orders')
            ->joinSub($links, 'links', 'links.order_id', '=', 'orders.id')
            ->leftJoinSub($payments, 'payments', 'payments.order_id', '=', 'orders.id')
            ->where(fn ($query) => $query->where('orders.method', 'Invoice')->orWhereNotNull('payments.order_id'))
            ->select('orders.id', 'orders.total', 'orders.method', 'orders.status', 'orders.created_at', 'links.customer_id')
            ->selectRaw('COALESCE(payments.received, 0) as received, COALESCE(payments.payment_count, 0) as payment_count, payments.last_payment')
            ->selectRaw("CASE WHEN orders.method = 'Invoice' THEN orders.total ELSE 0 END as invoiced")
            ->selectRaw("$balance as balance")
            ->selectRaw("CASE WHEN $collectible AND $balance > 0 THEN $balance ELSE 0 END as outstanding")
            ->selectRaw("CASE WHEN $collectible AND $balance < 0 THEN -($balance) ELSE 0 END as overpaid");
    }

    public function customers(): Builder
    {
        $totals = DB::query()->fromSub($this->invoices(), 'ledger')
            ->selectRaw('customer_id, COUNT(*) as invoice_count, SUM(invoiced) as invoiced, SUM(received) as received, SUM(outstanding) as outstanding, SUM(overpaid) as overpaid, SUM(payment_count) as payment_count, MAX(last_payment) as last_payment')
            ->groupBy('customer_id');

        // Compare receipts and item selling totals for the same active invoices.
        // Aggregate items before joining: installments and duplicate customer links
        // must not multiply them. Retail values are list prices, not actual costs.
        $items = DB::table('order_product')
            ->selectRaw('order_id, SUM(price * qty) as item_total')
            ->groupBy('order_id');
        $profit = DB::query()->fromSub($this->invoices(), 'profit_invoices')
            ->leftJoinSub($items, 'invoice_items', 'invoice_items.order_id', '=', 'profit_invoices.id')
            ->where('profit_invoices.method', 'Invoice')
            ->where(fn ($query) => $query->whereIn('profit_invoices.status', [0, 1])->orWhereNull('profit_invoices.status'))
            ->selectRaw('profit_invoices.customer_id, SUM(profit_invoices.received) as received, SUM(COALESCE(invoice_items.item_total, 0)) as item_total')
            ->groupBy('profit_invoices.customer_id');

        return DB::table('customers')->joinSub($totals, 'totals', 'totals.customer_id', '=', 'customers.id')
            ->leftJoinSub($profit, 'invoice_profit', 'invoice_profit.customer_id', '=', 'customers.id')
            ->select('customers.id', 'customers.company', 'totals.*')
            ->selectRaw('COALESCE(invoice_profit.item_total, 0) as invoice_item_total, ROUND(COALESCE(invoice_profit.received, 0) - COALESCE(invoice_profit.item_total, 0), 2) as profit');
    }

    public function paymentsFor(int $customerId): Builder
    {
        return DB::table('order_payment')
            ->whereIn('order_id', DB::table('customer_order')->select('order_id')->where('customer_id', $customerId));
    }
}
