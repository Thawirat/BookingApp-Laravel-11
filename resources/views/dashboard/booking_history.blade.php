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
            <form action="{{ route('booking_history') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">ค้นหา</label>
                    <input class="form-control" type="text" name="search" value="{{ request('search') }}"
                        placeholder="ชื่อ/อีเมล/รหัสการจอง">
                </div>
                <div class="col-md-2">
                    <label for="status_id" class="form-label">สถานะ</label>
                    <select name="status_id" class="form-select">
                        <option value="">ทั้งหมด</option>
                        <option value="6" {{ request('status_id') == '6' ? 'selected' : '' }}>เสร็จสิ้น</option>
                        <option value="5" {{ request('status_id') == '5' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="booking_date" class="form-label">วันที่</label>
                    <input type="date" name="booking_date" value="{{ request('booking_date') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">เรียงลำดับ</label>
                    <select name="sort" class="form-select">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ล่าสุด</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>เก่าสุด</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary mt-4 w-100"><i class="fas fa-search"></i> ค้นหา</button>
                    <a href="{{ route('booking_history') }}" class="btn btn-secondary mt-4">ล้าง</a>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-history me-2"></i> รายการประวัติการจอง
                            </h5>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-primary btn-sm me-2" id="openCalendar">
                                    <i class="fas fa-calendar-alt"></i> เลือกวันที่
                                </button>
                                <input type="text" id="datepicker" class="form-control"
                                    style="max-width: 150px; opacity: 0; position: absolute;">
                                <a href="{{ route('booking_db') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-calendar-day"></i> การจองปัจจุบัน
                                </a>
                            </div>
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
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">ลำดับที่</th>
                                            <th class="text-center">รหัสการจอง</th>
                                            <th class="text-center">ผู้จองห้อง</th>
                                            <th class="text-center">เบอร์โทรศัพท์</th>
                                            <th class="text-center">วันเวลาที่จอง</th>
                                            <th class="text-center">วันเวลาที่สิ้นสุดจอง</th>
                                            {{-- <th>สถานะการชำระเงิน</th> --}}
                                            <th class="text-center">สถานะการจอง</th>
                                            <th class="text-center">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                                                </td>
                                                <td><span class="badge bg-light text-dark">{{ $booking->id }}</span></td>
                                                <td>
                                                    <div class="fw-bold">{{ $booking->external_name }}</div>
                                                    <small class="text-muted">{{ $booking->external_email }}</small>
                                                </td>
                                                <td>{{ $booking->external_phone }}</td>
                                                <td>
                                                    <div><i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div><i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                                    </small>
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
