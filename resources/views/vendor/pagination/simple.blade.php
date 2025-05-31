@if ($paginator->hasPages())
    <ul class="pagination flex justify-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled px-3 py-1 bg-gray-200 text-gray-500 rounded">«</li>
        @else
            <li>
                <a class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" href="{{ $paginator->previousPageUrl() }}?tabla=usuarios" rel="prev">«</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Dots --}}
            @if (is_string($element))
                <li class="px-3 py-1 text-gray-500">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="px-3 py-1 bg-blue-700 text-white rounded">{{ $page }}</li>
                    @else
                        <li>
                            <a class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" href="{{ $url }}?tabla=usuarios">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" href="{{ $paginator->nextPageUrl() }}?tabla=usuarios" rel="next">»</a>
            </li>
        @else
            <li class="disabled px-3 py-1 bg-gray-200 text-gray-500 rounded">»</li>
        @endif
    </ul>
@endif
