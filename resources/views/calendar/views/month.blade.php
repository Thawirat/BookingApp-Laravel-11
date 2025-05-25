<div class="calendar-month">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>อาทิตย์</th>
                <th>จันทร์</th>
                <th>อังคาร</th>
                <th>พุธ</th>
                <th>พฤหัสบดี</th>
                <th>ศุกร์</th>
                <th>เสาร์</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calendarData as $week)
                <tr>
                    @foreach ($week as $day)
                        <td class="{{ $day['today'] ? 'bg-light' : '' }} {{ $day['currentMonth'] ? '' : 'text-muted' }}">
                            <div class="d-flex justify-content-between">
                                <span>{{ $day['day'] }}</span>

                                @php
                                    $visibleBookings = array_filter($day['bookings'], function ($booking) {
                                        return !in_array($booking->status_id, [1, 2]); // ซ่อนสถานะ 1 และ 2
                                    });
                                @endphp

                                @if (count($visibleBookings) > 0)
                                    <span class="badge bg-primary rounded-pill">{{ count($visibleBookings) }}</span>
                                @endif
                            </div>

                            {{-- แสดง booking แบบเป็นจุด --}}
                            <div class="day-events mt-1 d-flex flex-wrap gap-1">
                                @foreach ($visibleBookings as $booking)
                                    <div class="event-dot rounded-circle"
                                        style="width: 12px; height: 12px; background-color: {{ $booking->statusColor }}; cursor: pointer;"
                                        data-bs-toggle="tooltip" data-bs-html="true"
                                        title="<strong>{{ $booking->room_name }}</strong><br>
                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                ผู้จอง: {{ $booking->external_name }}">
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<style>
    .calendar-table {
        table-layout: fixed;
        width: 100%;
        font-size: 0.85rem;
    }

    .calendar-table td {
        height: 100px;
        vertical-align: top;
        padding: 6px 4px;
        position: relative;
    }

    .event-dot {
        width: 10px;
        height: 10px;
        display: inline-block;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .event-dot:hover {
        transform: scale(1.5);
        z-index: 2;
    }

    @media (max-width: 768px) {
        .calendar-table td {
            height: 80px;
            font-size: 0.75rem;
            padding: 4px;
        }

        .event-dot {
            width: 8px;
            height: 8px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
