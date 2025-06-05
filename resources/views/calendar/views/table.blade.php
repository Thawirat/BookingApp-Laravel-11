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
                @forelse ($tableRooms as $room)
                    <tr>
                        <td class="text-start fw-semibold sticky-col bg-light">
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $room->room_name }}</span>
                                <small class="text-muted">{{ $room->building->building_name ?? '' }}</small>
                            </div>
                        </td>

                        {{-- ✅ ลูปวันที่ทีละ cell --}}
                        @foreach ($tableDates as $date)
                            <td class="align-top">
                                @php
                                    $roomId = $room->room_id;
                                    $dateStr = $date['date'];
                                @endphp

                                @if (!empty($tableBookingData[$roomId][$dateStr]))
                                    @foreach ($tableBookingData[$roomId][$dateStr] as $booking)
                                        <div class="booking-item mb-2 p-2 border rounded"
                                            style="background-color: {{ $booking['statusColor'] }}20; border-color: {{ $booking['statusColor'] }}!important;"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="ผู้จอง: {{ $booking['user_name'] }}">
                                            <div class="booking-time fw-bold small">{{ $booking['time'] }}</div>
                                            <span class="badge small mt-1"
                                                style="background-color: {{ $booking['statusColor'] }}; color: white;">
                                                {{ $booking['status_name'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($tableDates) + 1 }}" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            ไม่มีข้อมูลห้องในอาคารที่เลือก
                        </td>
                    </tr>
                @endforelse
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
    }

    .booking-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        }

        .booking-time,
        .booking-title {
            font-size: 0.65rem;
        }

        .badge {
            font-size: 0.6rem;
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

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // เพิ่ม click event สำหรับ booking items
        document.querySelectorAll('.booking-item').forEach(function(item) {
            item.addEventListener('click', function() {
                // สามารถเพิ่ม modal หรือ action อื่นๆ ได้ที่นี่
                console.log('Booking clicked:', this);
            });
        });
    });
</script>
