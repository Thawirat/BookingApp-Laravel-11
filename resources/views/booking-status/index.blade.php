@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold d-flex align-items-center">
            <i class="fas fa-calendar-check me-2 fs-4"></i> สถานะการจองห้องของฉัน
        </h2>
        @if ($bookings->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered shadow-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th class="text-center">ลำดับที่</th>
                            <th class="text-center">รหัสการจอง</th>
                            <th class="text-center">ห้อง</th>
                            <th class="text-center">อาคาร</th>
                            <th class="text-center">วันที่จอง</th>
                            <th class="text-center">วันที่เริ่มต้น-สิ้นสุด</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">สถานะการชำระเงิน</th>
                            <th class="text-center">รายละเอียด</th>
                            <th class="text-center">PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $booking->id }}</td>
                                <td class="text-center">{{ $booking->room_name ?? '-' }}</td>
                                <td class="text-center">{{ $booking->building_name ?? '-' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <div><strong>เริ่ม:</strong>
                                        วันที่
                                        {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                        น.</div>
                                    <div><strong>สิ้นสุด:</strong>
                                        วันที่
                                        {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                        น.</div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-{{ $booking->status->status_name === 'อนุมัติ' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                        {{ $booking->status->status_name }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass =
                                            [
                                                'paid' => 'bg-success',
                                                'pending' => 'bg-info text-dark',
                                                'unpaid' => 'bg-warning text-dark',
                                                'cancelled' => 'bg-danger',
                                            ][$booking->payment_status] ?? 'bg-secondary';

                                        $statusText =
                                            [
                                                'paid' => 'ชำระแล้ว',
                                                'pending' => 'รอตรวจสอบ',
                                                'unpaid' => 'ยังไม่ชำระ',
                                                'cancelled' => 'ยกเลิก',
                                            ][$booking->payment_status] ?? 'ยังไม่ชำระ';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    <div class="dropdown mt-2">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            จัดการชำระเงิน
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#paymentModal{{ $booking->id }}">
                                                    <i class="bi bi-wallet2 me-2"></i>ดู/อัปโหลดหลักฐานชำระเงิน
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                    onclick="confirmCancel({{ $booking->id }})">
                                                    <i class="bi bi-x-circle me-2"></i>ยกเลิกการจอง
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @include('booking-status.slip-for-status')
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#detailsModal{{ $booking->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('bookings.download.pdf', $booking->id) }}"
                                        class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>
                                    </a>
                                </td>
                                @include('booking-status.modal')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> ยังไม่มีการจองในระบบ
            </div>
        @endif
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
