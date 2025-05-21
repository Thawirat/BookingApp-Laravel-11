@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="fas fa-calendar-check me-2"></i>ประวัติการจองห้องของฉัน
        </h2>
        @if ($bookings->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered shadow-sm">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>ห้อง</th>
                            <th>อาคาร</th>
                            <th>วันที่จอง</th>
                            <th>เริ่มต้น</th>
                            <th>สิ้นสุด</th>
                            <th>สถานะ</th>
                            <th>การชำระเงิน</th>
                            <th>รายละเอียด</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-center">{{ $booking->room_name ?? '-' }}</td>
                                <td class="text-center">{{ $booking->building_name ?? '-' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($booking->crated_add)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-{{ $booking->status->status_name === 'อนุมัติ' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                        {{ $booking->status->status_name }}
                                    </span>
                                </td>
                                {{-- สถานะการชำระเงิน --}}
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
                                </td>
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
                <i class="fas fa-info-circle me-1"></i> ยังไม่มีประวัติการจองในระบบ
            </div>
        @endif
    </div>
@endsection
