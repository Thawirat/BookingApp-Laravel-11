@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="fas fa-calendar-check me-2"></i>สถานะการจองห้องของฉัน
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
                            <th>รายละเอียด</th>
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
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#detailsModal{{ $booking->id }}">
                                        <i class="fas fa-eye"></i> ดู
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('booking-status.modal')
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
