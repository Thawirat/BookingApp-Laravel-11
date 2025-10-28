<div class="calendar-container">
    <div class="calendar-header">
        <div class="weekday">อาทิตย์</div>
        <div class="weekday">จันทร์</div>
        <div class="weekday">อังคาร</div>
        <div class="weekday">พุธ</div>
        <div class="weekday">พฤหัสบดี</div>
        <div class="weekday">ศุกร์</div>
        <div class="weekday">เสาร์</div>
    </div>

    <div class="calendar-grid">
        <?php $__currentLoopData = $calendarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div
                    class="calendar-day <?php echo e($day['today'] ? 'today' : ''); ?> <?php echo e($day['currentMonth'] ? '' : 'other-month'); ?>">
                    <div class="day-header">
                        <span class="day-number"><?php echo e($day['day']); ?></span>
                    </div>

                    <div class="day-content">
                        <?php
                            $bookingCount = 0;
                            $maxVisible = 3;
                        ?>
                        <?php $__currentLoopData = $allBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $startDate = \Carbon\Carbon::parse($booking->booking_start)->startOfDay();
                                $endDate = \Carbon\Carbon::parse($booking->booking_end)->startOfDay();
                                $currentDate = \Carbon\Carbon::parse($day['date'])->startOfDay();
                                $isInRange = $currentDate->between($startDate, $endDate);
                                $isSameDay = $startDate->equalTo($endDate);
                                $isVisible = !in_array($booking->status_id, [1, 2, 5, 6]);
                                $isStart = $currentDate->equalTo($startDate);
                                $isEnd = $currentDate->equalTo($endDate);
                                $isMiddle = $isInRange && !$isStart && !$isEnd;
                            ?>

                            <?php if($isVisible && $isInRange): ?>
                                <?php $bookingCount++; ?>

                                <?php
                                    $eventClass = 'event';
                                    if ($isStart && $isEnd) {
                                        $eventClass .= ' event-start event-end';
                                    } elseif ($isStart) {
                                        $eventClass .= ' event-start';
                                    } elseif ($isEnd) {
                                        $eventClass .= ' event-end';
                                    } elseif ($isMiddle) {
                                        $eventClass .= ' event-middle';
                                    }
                                ?>

                                <div class="<?php echo e($eventClass); ?> <?php echo e($bookingCount > $maxVisible ? 'd-none more-booking' : ''); ?>"
                                    style="background-color: <?php echo e($booking->statusColor); ?>;" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip"
                                    title="<?php echo e($booking->room_name); ?> (<?php echo e($booking->external_name); ?>) <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->locale('th')->copy()->addYears(543)->isoFormat('D/MM/YYYY HH:mm')); ?>

 - <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->locale('th')->copy()->addYears(543)->isoFormat(' D/MM/YYYY HH:mm')); ?>">
                                    <span class="event-title"><?php echo e($booking->room_name); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($bookingCount > $maxVisible): ?>
                            <button class="btn btn-sm btn-link p-0 mt-1 show-more-bookings">+ ดูการจองอีก
                                <?php echo e($bookingCount - $maxVisible); ?> รายการ</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<style>
    .calendar-container {
        background: white;
        border-radius: 8px;
        padding: 15px;
        font-family: 'Kanit', sans-serif;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
        background: #f8f9fa;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 0;
    }

    .weekday {
        text-align: center;
        font-weight: 500;
        color: #495057;
        padding: 8px 0;
        border-right: 1px solid #dee2e6;
    }

    .weekday:last-child {
        border-right: none;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
        border: 1px solid #dee2e6;
        border-top: none;
    }

    .calendar-day {
        background: white;
        min-height: 100px;
        padding: 5px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        position: relative;
    }

    .calendar-day:nth-child(7n) {
        border-right: none;
    }

    .day-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .day-number {
        font-size: 0.9rem;
        color: #495057;
        font-weight: 500;
    }

    .today .day-number {
        background: #0d6efd;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .other-month {
        background: #f8f9fa;
        color: #adb5bd;
    }

    .day-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
        position: relative;
    }

    .event {
        font-size: 0.75rem;
        color: white;
        cursor: pointer;
        position: relative;
        z-index: 2;
        font-weight: 500;
    }

    .event-dot {
        width: auto;
        height: 20px;
        display: flex;
        align-items: center;
        padding: 0 6px;
        border-radius: 3px;
    }

    .event-bar {
        height: 22px;
        margin: 1px 0;
        position: relative;
        display: flex;
        align-items: center;
        padding: 0 6px;
        /* ขยายแท่งให้เต็มความกว้างและข้ามช่องว่าง */
        margin-left: -5px;
        margin-right: -5px;
        width: calc(100% + 10px);
    }

    /* วันเริ่มต้น - มีมุมโค้งซ้ายและแสดงชื่อ */
    .event-start {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        padding-left: 8px;
        /* ขยายไปถึงขอบขวาของเซลล์ */
        margin-right: -6px;
        width: calc(100% + 11px);
    }

    /* วันสิ้นสุด - มีมุมโค้งขวา */
    .event-end {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        padding-right: 8px;
        /* ขยายไปถึงขอบซ้ายของเซลล์ */
        margin-left: -6px;
        width: calc(100% + 11px);
    }

    /* วันกลาง - ไม่มีมุมโค้งและขยายเต็มช่วง */
    .event-middle {
        border-radius: 0;
        margin-left: -6px;
        margin-right: -6px;
        width: calc(100% + 12px);
    }

    /* เฉพาะวันเริ่มต้นที่เป็นวันเดียวกับวันสิ้นสุด */
    .event-start.event-end {
        border-radius: 4px;
        margin-left: -5px;
        margin-right: -5px;
        width: calc(100% + 10px);
    }

    .event-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    .custom-tooltip {
        font-size: 0.8rem;
        max-width: 300px;
    }

    /* สำหรับเหตุการณ์ที่ข้ามเซลล์ */
    .event-bar::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        background: inherit;
        z-index: -1;
    }

    .event-start::before {
        left: 0;
        right: -1px;
    }

    .event-middle::before {
        left: -1px;
        right: -1px;
    }

    .event-end::before {
        left: -1px;
        right: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .calendar-container {
            padding: 10px;
        }

        .calendar-day {
            min-height: 80px;
            font-size: 0.8rem;
            padding: 3px;
        }

        .event {
            font-size: 0.7rem;
        }

        .event-dot {
            height: 16px;
            padding: 0 4px;
        }

        .event-bar {
            height: 18px;
            padding: 0 4px;
        }

        .day-number {
            font-size: 0.8rem;
        }

        .event-start {
            padding-left: 6px;
        }

        .event-end {
            padding-right: 6px;
        }
    }

    /* เพิ่ม hover effect */
    .event:hover {
        opacity: 0.8;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    /* ปรับสีให้สวยขึ้น */
    .event {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips with custom options
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true,
                placement: 'top',
                container: 'body'
            });
        });
        document.querySelectorAll('.show-more-bookings').forEach(button => {
            button.addEventListener('click', function() {
                const parent = button.closest('.calendar-day');
                const hiddenBookings = parent.querySelectorAll('.more-booking');
                hiddenBookings.forEach(item => item.classList.remove('d-none'));
                button.remove();
            });
        });
        // Add click event for events
        document.querySelectorAll('.event').forEach(event => {
            event.addEventListener('click', function() {
                // Show more details or open modal
                const tooltip = bootstrap.Tooltip.getInstance(event);
                if (tooltip) {
                    tooltip.hide();
                }
            });
        });
    });
</script>
<?php /**PATH /var/www/html/resources/views/calendar/views/month.blade.php ENDPATH**/ ?>