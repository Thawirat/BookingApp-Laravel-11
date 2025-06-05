<!-- ตารางการจองห้อง -->
<div class="calendar-table">
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="building_id" class="form-label fw-bold">เลือกอาคาร:</label>
            <select id="building_id" class="form-control">
                <option value="">ทั้งหมด</option>
                @foreach ($buildings as $building)
                    <option value="{{ $building->building_id }}"
                        {{ $building_id == $building->building_id ? 'selected' : '' }}>
                        {{ $building->building_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="table_date" class="form-label fw-bold">เลือกวันที่:</label>
            <input type="date" id="table_date" class="form-control" value="{{ $currentDate }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="button" class="btn btn-primary" onclick="filterTable()">
                <i class="bi bi-funnel me-1"></i>กรองข้อมูล
            </button>
        </div>
    </div>

    <!-- ตารางการจอง -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-start sticky-col">ห้อง</th>
                    @foreach ($tableDates as $date)
                        <th class="{{ $date['is_today'] ? 'bg-info text-white' : '' }}">
                            {{ $date['day_th'] }}<br>
                            <small>{{ $date['day_full'] }}</small>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($tableRooms as $room)
                    <tr>
                        <td class="text-start fw-semibold sticky-col bg-light">
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $room->room_name }}</span>
                                <small class="text-muted">{{ $room->building->building_name ?? '' }}</small>
                            </div>
                        </td>

                        @php
                            $occupiedCells = array_fill(0, count($tableDates), false);
                            $roomBookings = $tableBookingData[$room->room_id] ?? [];

                            // เรียงการจองตาม start_index
                            usort($roomBookings, function ($a, $b) {
                                return $a['start_index'] <=> $b['start_index'];
                            });
                        @endphp

                        @for ($dayIndex = 0; $dayIndex < count($tableDates); $dayIndex++)
                            @if ($occupiedCells[$dayIndex])
                                @continue
                            @endif

                            @php
                                $hasBooking = false;
                                $bookingToShow = null;

                                // หาการจองที่เริ่มต้นในวันนี้
                                foreach ($roomBookings as $booking) {
                                    if ($booking['start_index'] == $dayIndex) {
                                        $hasBooking = true;
                                        $bookingToShow = $booking;

                                        // ทำเครื่องหมายเซลล์ที่จะถูกใช้
                                        for ($i = 0; $i < $booking['colspan']; $i++) {
                                            if ($dayIndex + $i < count($tableDates)) {
                                                $occupiedCells[$dayIndex + $i] = true;
                                            }
                                        }
                                        break;
                                    }
                                }
                            @endphp

                            @if ($hasBooking && $bookingToShow)
                                <td colspan="{{ $bookingToShow['colspan'] }}">
                                    <div class="booking-item p-2 border rounded position-relative"
                                        style="background-color: {{ $bookingToShow['statusColor'] }}20; border-color: {{ $bookingToShow['statusColor'] }}!important;"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-booking-id="{{ $bookingToShow['id'] }}"
                                        title="ผู้จอง: {{ $bookingToShow['user_name'] }}&#10;ช่วงเวลา: {{ $bookingToShow['time'] }}&#10;ระยะเวลา: {{ $bookingToShow['date_range'] }}">

                                        <div class="booking-content">
                                            <div class="booking-time fw-bold small">{{ $bookingToShow['time'] }}</div>
                                            @if ($bookingToShow['colspan'] > 1)
                                                <div class="booking-duration text-muted" style="font-size: 0.7rem;">
                                                    {{ $bookingToShow['date_range'] }}
                                                </div>
                                            @endif
                                            <span class="badge small mt-1"
                                                style="background-color: {{ $bookingToShow['statusColor'] }}; color: white;">
                                                {{ $bookingToShow['status_name'] }}
                                            </span>
                                        </div>

                                        @if ($bookingToShow['colspan'] > 1)
                                            <div class="booking-extend-indicator"></div>
                                        @endif
                                    </div>
                                </td>
                            @else
                                <td><span class="text-muted small">-</span></td>
                            @endif
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (!empty($tableRooms) && count($tableRooms) > 0)
        <div class="mt-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                แสดงข้อมูล {{ count($tableRooms) }} ห้อง
                สำหรับวันที่ {{ $tableDates[0]['day_full'] ?? '' }} - {{ $tableDates[6]['day_full'] ?? '' }}
            </small>
        </div>
    @endif
</div>

<style>
    .booking-item {
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .booking-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .booking-content {
        text-align: center;
        width: 100%;
    }

    .booking-extend-indicator::after {
        content: '';
        position: absolute;
        right: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 8px solid currentColor;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        opacity: 0.3;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        background-color: #f8f9fa !important;
    }

    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }

    .booking-title {
        line-height: 1.2;
    }

    .booking-time {
        color: #495057;
    }

    .booking-duration {
        margin: 2px 0;
        font-weight: 500;
    }

    /* Multi-day booking styling */
    .booking-item[data-colspan="2"] {
        background: linear-gradient(90deg, currentColor 0%, rgba(255, 255, 255, 0.1) 100%);
    }

    .booking-item[data-colspan="3"] {
        background: linear-gradient(90deg, currentColor 0%, rgba(255, 255, 255, 0.1) 50%, currentColor 100%);
    }

    /* เพิ่ม responsive สำหรับ mobile */
    @media (max-width: 768px) {

        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }

        .booking-item {
            padding: 0.25rem;
            margin-bottom: 0.25rem;
            min-height: 50px;
        }

        .booking-time,
        .booking-title {
            font-size: 0.65rem;
        }

        .booking-duration {
            font-size: 0.6rem;
        }

        .badge {
            font-size: 0.6rem;
        }

        .booking-extend-indicator::after {
            border-left: 6px solid currentColor;
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
        }
    }
</style>

<script>
    // กรองข้อมูลตารางเมื่อเลือกอาคารหรือวันที่
    function filterTable() {
        const building_id = document.getElementById('building_id').value;
        const date = document.getElementById('table_date').value;

        // สร้าง URL ใหม่
        const params = new URLSearchParams();
        params.set('view', 'table');
        if (building_id) params.set('building_id', building_id);
        if (date) params.set('date', date);

        // Redirect ไปยัง URL ใหม่
        window.location.href = "{{ route('calendar.index') }}?" + params.toString();
    }

    // เพิ่ม event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Auto filter เมื่อเปลี่ยนค่า
        document.getElementById('building_id').addEventListener('change', filterTable);

        // Initialize tooltips with HTML support
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true,
                placement: 'top'
            });
        });

        // เพิ่ม click event สำหรับ booking items
        document.querySelectorAll('.booking-item').forEach(function(item) {
            item.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                console.log('Booking clicked:', bookingId);
                // สามารถเพิ่ม modal หรือ action อื่นๆ ได้ที่นี่
                // เช่น เปิด modal แสดงรายละเอียดการจอง
                // showBookingDetail(bookingId);
            });
        });

        // เพิ่ม visual effect สำหรับการจองหลายวัน
        document.querySelectorAll('.booking-item').forEach(function(item) {
            const colspan = item.closest('td').getAttribute('colspan');
            if (colspan && parseInt(colspan) > 1) {
                item.setAttribute('data-colspan', colspan);
                item.classList.add('multi-day-booking');
            }
        });
    });

    // ฟังก์ชันสำหรับแสดงรายละเอียดการจอง (เพิ่มเติมในอนาคต)
    function showBookingDetail(bookingId) {
        // TODO: เปิด modal หรือไปหน้ารายละเอียด
        console.log('Show booking detail for ID:', bookingId);
    }
</script>
