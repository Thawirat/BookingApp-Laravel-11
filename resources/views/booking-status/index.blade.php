@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 d-flex align-items-center">
            <i class="fas fa-calendar-check me-2 fs-4"></i> สถานะการจองห้องของฉัน
        </h2>
        <form method="GET" action="{{ route('my-bookings') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="ค้นหาชื่อห้อง/อาคาร...">
                </div>
                <div class="btn-group col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>
                    <a href="{{ route('my-bookings') }}" class="btn btn-secondary">ล้างการค้นหา</a>
                </div>
                <div class="col-md-2">
                    <select name="status_id" class="form-select" onchange="this.form.submit()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="3" {{ request('status_id') == '3' ? 'selected' : '' }}>รออนุมัติ</option>
                        <option value="4" {{ request('status_id') == '4' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                        <option value="5" {{ request('status_id') == '5' ? 'selected' : '' }}>ยกเลิกการจอง</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="booking_date" value="{{ request('booking_date') }}" class="form-control"
                        onchange="this.form.submit()">
                </div>
            </div>
        </form>
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
                                                    class="badge bg-{{ $booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : ($booking->status->status_name === 'ยกเลิกการจอง' ? 'danger' : 'secondary')) }}">
                                                    {{ $booking->status->status_name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-auto d-flex flex-column gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                                <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                            </button>
                                            @include('booking-status.modal')
                                            <a href="{{ route('mybookings.download.pdf', $booking->id) }}"
                                                class="btn btn-outline-danger btn-sm flex-grow-1">
                                                <i class="fas fa-file-pdf me-1"></i> ดาวน์โหลด PDF
                                            </a>
                                            <form id="cancel-form-{{ $booking->id }}"
                                                action="{{ route('mybookings.cancel', $booking->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="d-grid gap-2">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm flex-grow-1 cancel-booking-btn"
                                                        data-id="{{ $booking->id }}"
                                                        data-room="{{ $booking->room->room_name ?? 'ห้อง' }}"
                                                        data-status="{{ $booking->status_id }}"
                                                        data-url="{{ route('mybookings.cancel', $booking->id) }}">
                                                        <i class="fas fa-times-circle me-1"></i> ยกเลิกการจอง
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif (request()->hasAny(['q', 'status_id', 'booking_date']))
                <div class="alert alert-warning">
                    <i class="fas fa-search-minus me-1"></i> ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i> ยังไม่มีการจองในระบบ
                </div>
            @endif
            <div class="mt-3">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.cancel-booking-btn').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.dataset.id;
                const cancelUrl = this.dataset.url;
                const roomName = this.dataset.room;
                const statusId = parseInt(this.dataset.status);

                if (statusId === 4) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถยกเลิกได้',
                        text: `การจองห้อง "${roomName}" ได้รับการอนุมัติแล้ว ไม่สามารถยกเลิกได้`,
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                Swal.fire({
                    title: 'ยืนยันการยกเลิก?',
                    html: `คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองห้อง <strong>${roomName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, ยกเลิกเลย',
                    cancelButtonText: 'ไม่',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`cancel-form-${bookingId}`);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
