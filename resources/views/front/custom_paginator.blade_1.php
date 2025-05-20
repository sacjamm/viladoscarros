<div class="col-12">
    <div class="psp_pagination d-flex justify-content-center">
        <nav aria-label="">
            <ul class="pagination">

                @if ($paginator->onFirstPage())

                @else
                    <li class="page-item">
                        <a href="javascript:;"  onclick="loadAjaxListing('{{ $paginator->previousPageUrl() }}')" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (count($element) < 2)

                    @else
                        @foreach ($element as $key => $el)
                            @if ($key == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><a class="page-link" href="javascript::void()">{{ $key }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="javascript:;" onclick="loadAjaxListing('{{ $el }}')">{{ $key }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="javascript:;" onclick="loadAjaxListing('{{ $paginator->nextPageUrl() }}')"><i class="fas fa-chevron-right"></i></a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
</div>
