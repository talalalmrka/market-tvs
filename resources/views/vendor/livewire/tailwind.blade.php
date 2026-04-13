@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';

// Set the number of links to show on each side
$linksToShow = 2; // Number of links before and after the current page
$current = $paginator->currentPage();
$last = $paginator->lastPage();
$start = max($current - $linksToShow, 1);
$end = min($current + $linksToShow, $last);
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex-1 md:flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 leading-5 dark:text-gray-400" aria-live="polite" aria-atomic="true">
                        <span>{{ __('Page') }}</span>
                        <span class="font-medium">{{ $paginator->currentPage() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->lastPage() }}</span>
                        <span class="sr-only">{{ $paginator->firstItem() }} {{ __('to') }} {{ $paginator->lastItem() }} {{ __('of') }} {{ $paginator->total() }} items.</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rtl:flex-row-reverse rounded-md shadow-sm">
                        {{-- First Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-300 cursor-not-allowed rounded-s-md leading-5 dark:bg-gray-700 dark:border-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <button type="button" wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-s-md leading-5 hover:text-gray-400 dark:bg-gray-800 dark:border-gray-600" title="{{ __('Go to first page') }}" aria-label="{{ __('Go to first page') }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-50 border border-gray-300 cursor-not-allowed leading-5 dark:bg-gray-700 dark:border-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-400 dark:bg-gray-800 dark:border-gray-600" aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        {{-- Page Numbers: only $linksToShow before and after the current page --}}
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" aria-label="{{ __('Current page, page :page', ['page' => $page]) }}">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-primary border border-primary cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600">{{ $page }}</span>
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring ring-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-gray-300 dark:active:bg-gray-700" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </button>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-400 dark:bg-gray-800 dark:border-gray-600" aria-label="{{ __('pagination.next') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-50 border border-gray-300 cursor-not-allowed leading-5 dark:bg-gray-700 dark:border-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif

                        {{-- Last Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="gotoPage({{ $paginator->lastPage() }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 dark:bg-gray-800 dark:border-gray-600" title="{{ __('Go to last page') }}" aria-label="{{ __('Go to last page') }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414zm6 0a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-50 border border-gray-300 cursor-not-allowed rounded-r-md leading-5 dark:bg-gray-700 dark:border-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414zm6 0a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L14.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
<div wire:loading
    class="fixed top-1/2 -translate-y-1/2 start-1/2 -translate-x-1/2 bg-primary text-white pill p-0 px-4">
    <i class="icon fg-loader-dots-scale text-2xl block"></i>
</div>

