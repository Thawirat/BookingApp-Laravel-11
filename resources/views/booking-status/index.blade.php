@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold d-flex align-items-center">
            <i class="fas fa-calendar-check me-2 fs-4"></i> สถานะการจองห้องของฉัน
        </h2>
        <div class="mb-5">
            @if (isset($bookings) && $bookings->count() > 0)
                <div class="booking-carousel d-flex overflow-auto pb-3">
                    @foreach ($bookings as $booking)
                        <div class="card me-3 flex-shrink-0" style="width: 300px;">
                            @if (!empty($booking->room) && !empty($booking->room->image))
                                <img src="{{ asset('storage/' . $booking->room->image) }}"
                                    alt="{{ $booking->room->room_name ?? 'Room Image' }}"
                                    class="img-fluid rounded-lg shadow-sm" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5"
                                    style="height: 180px;">
                                    <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                </div>
                            @endif
                            <div class="card-body p-3">
                                <h5 class="card-title">{{ $booking->room_name ?? '-' }}</h5>
                                <p class="card-text text-muted">
                                <div><strong>จองเมื่อ:</strong>
                                    {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}
                                </div>
                                <div><strong>อาคาร:</strong> {{ $booking->building_name ?? '-' }} </div>
                                <div><strong>เริ่มวันที่:</strong>
                                    {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                    น.</div>
                                <div><strong>ถึงวันที่:</strong>
                                    {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                    น.</div>
                                <div><strong>สถานะ:</strong>
                                    <span
                                        class="badge bg-{{ $booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                        {{ $booking->status->status_name ?? '-' }}
                                    </span>
                                </div>
                                </p>
                                <div class='items-center mt-4'><button type="button" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                        <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                    </button> @include('booking-status.modal')</div>
                                <a href="{{ route('bookings.download.pdf', $booking->id) }}"
                                        class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>
                                    </a>
                            </div>
                        </div>
                    @endforeach
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
