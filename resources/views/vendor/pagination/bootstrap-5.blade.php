@if ($paginator->hasPages())
    <nav class="d-flex flex-column align-items-center mt-4">

        {{-- ข้อความ "กำลังแสดง..." --}}
        <div class="mb-2 text-center">
            <p class="small text-muted mb-0">
                กำลังแสดง
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                ถึง
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                จาก
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                รายการ
            </p>
        </div>

        {{-- ตัวแบ่งหน้า --}}
        <div class="d-flex justify-content-center">
            <ul class="pagination mb-0">
                {{-- ปุ่มก่อนหน้า --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="ก่อนหน้า">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="ก่อนหน้า">&lsaquo;</a>
                    </li>
                @endif

                {{-- หน้าทั้งหมด --}}
                @foreach ($elements as $element)
                    {{-- จุดไข่ปลา --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- ลูปแต่ละหน้า --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- ปุ่มถัดไป --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="ถัดไป">&rsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="ถัดไป">
                        <span class="page-link" aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
