@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 d-flex align-items-center">
            <i class="fas fa-calendar-check me-2"></i>ประวัติการจองห้องของฉัน
        </h2>
        <form method="GET" action="{{ route('bookings.history') }}" class="row g-2 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อห้อง/อาคาร/รหัสการจอง...">
                </div>
                <div class="btn-group col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> ค้นหา
                    </button>
                    <a href="{{ route('bookings.history') }}" class="btn btn-secondary">
                        ล้างการค้นหา
                    </a>
                </div>
                <div class="col-md-2">
                    <select name="status_id" class="form-select" onchange="this.form.submit()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="6" {{ request('status_id') == '6' ? 'selected' : '' }}>ดำเนินการเสร็จสิ้น
                        </option>
                        <option value="5" {{ request('status_id') == '5' ? 'selected' : '' }}>ยกเลิกการจอง</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>เรียงล่าสุด</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>เรียงเก่าสุด</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="booking_date" value="{{ request('booking_date') }}" class="form-control"
                        onchange="this.form.submit()">
                </div>
            </div>
        </form>
        @if ($bookings->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">ลำดับที่</th>
                            <th class="text-center">รหัสการจอง</th>
                            <th class="text-center">ชื่อห้อง</th>
                            <th class="text-center">อาคาร</th>
                            <th class="text-center">วันที่จอง</th>
                            <th class="text-center">วันที่เริ่มต้น-สิ้นสุด</th>
                            <th class="text-center">สถานะ</th>
                            {{-- <th class="text-center">การชำระเงิน</th> --}}
                            <th class="text-center">รายละเอียด</th>
                            <th class="text-center"><a href="{{ route('bookings.download.all.pdf') }}"
                                    class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-file-pdf me-1">ดาวโหลดทั้งหมด</i>
                                </a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $booking->ref_number }}</td>
                                <td class="text-center">{{ $booking->room_name ?? '-' }}</td>
                                <td class="text-center">{{ $booking->building_name ?? '-' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($booking->crated_at)->addyear(543)->format('d/m/Y') }}
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
                                        class="badge
                                        @if ($booking->status->status_name === 'อนุมัติ') bg-success
                                        @elseif ($booking->status->status_name === 'รอดำเนินการ')
                                            bg-warning text-dark
                                        @elseif ($booking->status->status_name === 'ดำเนินการเสร็จสิ้น')
                                            bg-primary
                                        @elseif ($booking->status->status_name === 'ยกเลิกการจอง')
                                            bg-danger
                                        @else
                                            bg-secondary @endif">
                                        {{ $booking->status->status_name }}
                                    </span>
                                </td>
                                {{-- สถานะการชำระเงิน --}}
                                {{-- <td class="text-center">
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
                                </td> --}}
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
        @elseif (request()->hasAny(['q', 'status_id', 'booking_date']))
            <div class="alert alert-warning">
                <i class="fas fa-search-minus me-1"></i> ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> ยังไม่มีประวัติการจองในระบบ
            </div>
        @endif
        <div class="mt-4">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
