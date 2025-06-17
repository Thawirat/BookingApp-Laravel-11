@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>ประวัติการจองห้อง</h2>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-book icon"></i>
                        <div class="details">
                            <h3>{{ $totalBookings }}</h3>
                            <p>จำนวนการจองทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-check-circle icon"></i>
                        <div class="details">
                            <h3>{{ $completedBookings }}</h3>
                            <p>การจองที่เสร็จสิ้น</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-times-circle icon"></i>
                        <div class="details">
                            <h3>{{ $cancelledBookings }}</h3>
                            <p>การจองที่ยกเลิก</p>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('booking_history') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="search" value="{{ request('search') }}"
                        placeholder="ชื่อ/อีเมล/รหัสการจอง...">
                </div>
                <div class="btn-group col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>
                    <a href="{{ route('booking_history') }}" class="btn btn-secondary">ล้างการค้นหา</a>
                </div>
                <div class="col-md-2">
                    <select name="status_id" class="form-select" onchange="this.form.submit()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="6" {{ request('status_id') == '6' ? 'selected' : '' }}>เสร็จสิ้นการจอง</option>
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
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-history me-2"></i> รายการประวัติการจอง
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-bordered shadow-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">ลำดับที่</th>
                                            <th class="text-center">รหัสการจอง</th>
                                            <th class="text-center">ผู้จองห้อง</th>
                                            <th class="text-center">เบอร์โทรศัพท์</th>
                                            <th class="text-center">ชื่อห้อง</th>
                                            <th class="text-center">วันที่จอง</th>
                                            <th class="text-center">วันที่เริ่มต้น-สิ้นสุด</th>
                                            {{-- <th>สถานะการชำระเงิน</th> --}}
                                            <th class="text-center">สถานะ</th>
                                            <th class="text-center">รายละเอียด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center fw-bold">{{ $booking->id }}</td>
                                                <td class="text-center">
                                                    <div class="fw-bold">{{ $booking->external_name }}</div>
                                                    <small class="text-muted">{{ $booking->external_email }}</small>
                                                </td>
                                                <td class="text-center">{{ $booking->external_phone }}</td>
                                                <td class="text-center">{{ $booking->room_name }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($booking->created_at)->addyear(543)->format('d/m/Y') }}
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
                                                {{-- <td>
                                            <span class="badge {{ $booking->payment_status == 'ชำระแล้ว' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $booking->payment_status }}
                                            </span>
                                        </td> --}}
                                                <td class="text-center">
                                                    <span
                                                        class="badge
                                                @if ($booking->status_id == 5) bg-danger
                                                @elseif($booking->status_id == 6) bg-primary
                                                @else bg-secondary @endif">
                                                        {{ $booking->status_name }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-outline-info btn-sm view-details"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailsModal{{ $booking->id }}">
                                                            <i class="fas fa-eye"></i> รายละเอียด
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('booking-status.modal')
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                                    <p>ไม่พบข้อมูลประวัติการจอง</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $bookings->appends(['search' => request('search'), 'booking_date' => request('booking_date')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var datepicker = document.getElementById("datepicker");

        var calendar = flatpickr(datepicker, {
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('booking_date') }}",
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    window.location.href =
                        `{{ route('booking_history') }}?booking_date=${dateStr}`;
                }
            }
        });

        document.getElementById("openCalendar").addEventListener("click", function() {
            calendar.open(); // เปิด Flatpickr ทันทีเมื่อกดปุ่ม
        });
    });
</script>
