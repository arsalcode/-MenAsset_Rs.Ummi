@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200 cursor-default leading-5 rounded-xl dark:text-slate-600 dark:bg-slate-800 dark:border-slate-700">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        @php($previousCursor = $paginator->previousCursor() ?? $paginator->cursor())
                        <button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $previousCursor?->encode() }}" wire:click="setPage('{{ $previousCursor?->encode() }}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 leading-5 rounded-xl hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 active:bg-emerald-100 transition ease-in-out duration-150 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">
                                {!! __('pagination.previous') !!}
                        </button>
                    @else
                        <button
                            type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 leading-5 rounded-xl hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 active:bg-emerald-100 transition ease-in-out duration-150 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">
                                {!! __('pagination.previous') !!}
                        </button>
                    @endif
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        @php($nextCursor = $paginator->nextCursor() ?? $paginator->cursor())
                        <button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $nextCursor?->encode() }}" wire:click="setPage('{{ $nextCursor?->encode() }}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 leading-5 rounded-xl hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 active:bg-emerald-100 transition ease-in-out duration-150 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">
                                {!! __('pagination.next') !!}
                        </button>
                    @else
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 leading-5 rounded-xl hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 active:bg-emerald-100 transition ease-in-out duration-150 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">
                                {!! __('pagination.next') !!}
                        </button>
                    @endif
                @else
                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200 cursor-default leading-5 rounded-xl dark:text-slate-600 dark:bg-slate-800 dark:border-slate-700">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </span>
        </nav>
    @endif
</div>
