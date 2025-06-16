@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 d-flex align-items-center">
            <i class="fas fa-calendar-check me-2 fs-4"></i> สถานะการจองห้องของฉัน
        </h2>
        <div class="mb-5">
            @if (isset($bookings) && $bookings->count() > 0)
                <div class="container mx-auto pb-3 py-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($bookings as $booking)
                            <div class="card h-100 border-0 shadow-sm flex flex-col">
                                <div class="position-relative">
                                    @if (!empty($booking->room) && !empty($booking->room->image))
                                        <img src="{{ asset('storage/' . $booking->room->image) }}"
                                            alt="{{ $booking->room->room_name ?? 'Room Image' }}"
                                            class="img-fluid rounded-top w-100" style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-top d-flex align-items-center justify-content-center"
                                            style="height: 180px;">
                                            <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="card-title">{{ $booking->room_name ?? '-' }}</h5>

                                        <div class="card-text text-muted mb-2">
                                            <div><strong>จองเมื่อ:</strong>
                                                {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}
                                            </div>
                                            <div><strong>อาคาร:</strong> {{ $booking->building_name ?? '-' }}</div>
                                            <div><strong>เริ่มวันที่:</strong>
                                                {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                                น.
                                            </div>
                                            <div><strong>ถึงวันที่:</strong>
                                                {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                                น.
                                            </div>
                                            <div><strong>สถานะ:</strong>
                                                <span
                                                    class="badge bg-{{ $booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                                    {{ $booking->status->status_name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-auto d-flex gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                                <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                            </button>
                                            @include('booking-status.modal')

                                            <a href="{{ route('bookings.download.pdf', $booking->id) }}"
                                                class="btn btn-outline-danger btn-sm flex-grow-1">
                                                <i class="fas fa-file-pdf me-1"></i> ดาวน์โหลด PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-muted">คุณยังไม่มีการจองห้อง</p>
            @endif
        </div>
    </div>
@endsection

<script>
    function confirmCancel(bookingId) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "การยกเลิกนี้จะไม่สามารถย้อนกลับได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ยกเลิก!',
            cancelButtonText: 'ไม่',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/booking/${bookingId}/cancel`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PATCH">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>
