<div class="ip-pagination">
    <span>{{ $paginator->total() ? $paginator->firstItem().'–'.$paginator->lastItem().' of '.number_format($paginator->total()) : '0 results' }}</span>
    @if ($paginator->hasPages())
        <nav aria-label="{{ $paginator->getPageName() === 'page' ? 'Customer' : ($paginator->getPageName() === 'paymentsPage' ? 'Payment' : 'Invoice') }} pages">
            <button type="button" class="ip-button ip-button-small" wire:click="previousPage('{{ $paginator->getPageName() }}')" @disabled($paginator->onFirstPage()) aria-label="Previous page">←</button>
            <span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
            <button type="button" class="ip-button ip-button-small" wire:click="nextPage('{{ $paginator->getPageName() }}')" @disabled(!$paginator->hasMorePages()) aria-label="Next page">→</button>
        </nav>
    @endif
</div>
