@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>จัดการการจองห้อง</h2>
                <div class="d-flex align-items-center">
                    <form action="{{ route('booking_db') }}" method="GET" class="d-flex">
                        <input class="search-bar me-2" placeholder="ค้นหาการจอง" type="text"
                            name="search"value="{{ request('search') }}" />
                        <button type="submit" class="icon-btn"><i class="fas fa-search"></i></button>
                    </form>
                </div>
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
                        <i class="fas fa-clock icon"></i>
                        <div class="details">
                            <h3>{{ $pendingBookings }}</h3>
                            <p>จำนวนการจองที่รอดำเนินการ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-check-circle icon"></i>
                        <div class="details">
                            <h3>{{ $confirmedBookings }}</h3>
                            <p>จำนวนการจองที่อุนมัติแล้ว</p>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('booking_db') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="search" value="{{ request('search') }}"
                        placeholder="ชื่อ/อีเมล/รหัสการจอง...">
                </div>
                <div class="btn-group col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>
                    <a href="{{ route('booking_db') }}" class="btn btn-secondary">ล้างการค้นหา</a>
                </div>
                <div class="col-md-2">
                    <select name="status_id" class="form-select" onchange="this.form.submit()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="3" {{ request('status_id') == '3' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="4" {{ request('status_id') == '4' ? 'selected' : '' }}>อนุมัติแล้ว</option>
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
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-list me-2"></i> รายการการจอง</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="alert"aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">ลำดับที่</th>
                                            <th class="text-center">รหัสการจอง</th>
                                            <th class="text-center">ห้องที่จอง</th>
                                            <th class="text-center">ผู้จองห้อง</th>
                                            <th class="text-center">เบอร์โทรศัพท์</th>
                                            <th class="text-center">วันที่จอง</th>
                                            <th class="text-center">วันที่เริ่มต้น-สิ้นสุดการจอง</th>
                                            {{-- <th class="text-center">สถานะการชำระเงิน</th> --}}
                                            <th class="text-center">สถานะการอนุมัติ</th>
                                            <th class="text-center">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr class="text-center">
                                                <td class="text-center">
                                                    {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                                                </td>
                                                <td><span class="badge bg-light text-dark">{{ $booking->booking_id }}</span>
                                                </td>
                                                <td><span class="fw-bold">{{ $booking->room_name }}</span></td>
                                                <td>
                                                    <div class="fw-bold">{{ $booking->external_name }}</div>
                                                    <small class="text-muted">{{ $booking->external_email }}</small>
                                                </td>
                                                <td>{{ $booking->external_phone }}</td>
                                                <td>
                                                    <div><i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->created_at)->addYear(543)->format('d/m/Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_start)->addYear(543)->format('d/m/Y') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($booking->booking_end)->addYear(543)->format('d/m/Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }}น.
                                                        -
                                                        {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}น.
                                                    </small>
                                                </td>
                                                {{-- <td class="text-center">
                                                    @include('component-dropdown.payment', ['booking' => $booking,])
                                                </td> --}}
                                                <td class="text-center">
                                                    @include('component-dropdown.accept', [
                                                        'booking' => $booking,
                                                    ])
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-outline-primary view-details"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailsModal{{ $booking->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        {{-- <a href="{{ route('mybookings.download.pdf', $booking->id) }}"
                                                            class="btn btn-outline-danger">
                                                            <i class="fas fa-file-pdf me-1"></i>
                                                        </a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('booking-status.modal')
                                            {{-- @include('booking.payment-slip') --}}
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                                    <p>ไม่พบข้อมูลการจอง</p>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var datepicker = document.getElementById("datepicker");

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            var calendar = flatpickr(datepicker, {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('booking_date') }}",
                onChange: function(selectedDates, dateStr, instance) {
                    if (dateStr) {
                        window.location.href = `{{ route('booking_db') }}?booking_date=${dateStr}`;
                    }
                }
            });

            document.getElementById("openCalendar").addEventListener("click", function() {
                calendar.open(); // เปิด Flatpickr ทันทีเมื่อกดปุ่ม
            });

            // ทำงานกับ modal หลายอัน
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    document.querySelectorAll(
                        'body > *:not([aria-hidden="true"]):not(.modal-backdrop)').forEach(
                        function(el) {
                            if (el !== modal) {
                                el.setAttribute('aria-hidden', 'true');
                            }
                        });
                    this.querySelector('[data-bs-dismiss="modal"]')?.focus();
                });

                modal.addEventListener('hidden.bs.modal', function() {
                    document.querySelectorAll('body > *[aria-hidden="true"]').forEach(function(el) {
                        el.removeAttribute('aria-hidden');
                    });
                });
            });
        });
        moment.locale('th');
    </script>
@endsection
