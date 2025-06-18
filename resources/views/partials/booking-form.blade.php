@extends('layouts.app')

@section('content')
    <!-- Main Container with Background -->
    <div class="container-fluid py-5"
        style="background-image: url('{{ asset('images/bg-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="container">
            <div class="row">
                <!-- Booking Form Section -->
                <div class="col-lg-8 mb-4">
                    <div class="card rounded-lg border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h4 class="mb-0 fw-bold">ข้อมูลผู้จอง <span class="text-danger">*</span></h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm"
                                enctype="multipart/form-data" onsubmit="return handleFormSubmit(event);">
                                @csrf
                                <!-- Hidden inputs -->
                                <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                                <input type="hidden" name="building_id" value="{{ $room->building_id }}">
                                <input type="hidden" name="room_name" value="{{ $room->room_name }}">
                                <input type="hidden" name="building_name"
                                    value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}">
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div class="row g-3">
                                    <!-- Building Name -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">อาคาร</label>
                                        <input class="form-control bg-light" type="text"
                                            value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}" readonly>
                                    </div>

                                    <!-- Room Name -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">ห้อง <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control bg-light" type="text" value="{{ $room->room_name }}"
                                            readonly>
                                    </div>

                                    <!-- User Name -->
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">ชื่อผู้จอง <span
                                                class="text-danger">*</span></label>
                                        @if (auth()->check())
                                            <input class="form-control" type="text" name="external_name"
                                                value="{{ auth()->user()->name }}">
                                        @else
                                            <input class="form-control" name="external_name" type="text" required>
                                        @endif
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">อีเมล <span
                                                class="text-danger">*</span></label>
                                        @if (auth()->check())
                                            <input class="form-control" type="email" name="external_email"
                                                value="{{ auth()->user()->email }}">
                                        @else
                                            <input class="form-control" type="email" name="external_email" required>
                                        @endif
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">เบอร์โทร <span
                                                class="text-danger">*</span></label>
                                        @if (auth()->check())
                                            <input class="form-control" type="tel" name="external_phone"
                                                value="{{ auth()->user()->phone_number ?? '' }}"
                                                {{ auth()->user()->phone_number ? '' : '' }}>
                                        @else
                                            <input class="form-control" type="tel" name="external_phone" required>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">ตำแหน่ง <span
                                                class="text-danger">*</span></label>
                                        @if (auth()->check())
                                            <input class="form-control" type="text" name="external_position"
                                                value="{{ auth()->user()->department ?? '' }}">
                                        @else
                                            <input class="form-control" type="text" name="external_position" required>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">ที่อยู่/หน่วยงาน <span
                                                class="text-danger">*</span></label>
                                        @if (auth()->check())
                                            <input class="form-control" type="text" name="external_address"
                                                value="{{ auth()->user()->address ?? '' }}">
                                        @else
                                            <input class="form-control" type="text" name="external_address" required>
                                        @endif
                                    </div>
                                    <!-- Reason -->
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">วัตถุประสงค์</label>
                                        <textarea name="reason" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="border-0 mt-4 mb-3">
                                        <div class="fw-bold">อุปกรณ์ในห้อง</div>
                                        @if ($room->equipments->isEmpty())
                                            <p>ไม่มีอุปกรณ์ในห้องนี้</p>
                                        @else
                                            <ul class="list-group list-group-flush">
                                                @foreach ($room->equipments as $equipment)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $equipment->name }}
                                                        <span class="badge bg-primary rounded-pill">
                                                            จำนวน{{ $equipment->quantity }} รายการ</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">รายละเอียดเพิ่มเติม(ถ้ามี)</label>
                                        <textarea name="booker_info" class="form-control" rows="3"
                                            placeholder="ระบุรายละเอียดเพิ่มเติม เช่น ต้องการวัสดุ/อุปกรณ์..."></textarea>
                                    </div>
                                    <!-- จำนวนผู้เข้าร่วม -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">จำนวนผู้เข้าร่วม <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="participant_count"
                                            min="1" required>
                                    </div>
                                </div>
                                <!-- Date Selection Box -->
                                <div class="border-0 mt-4 mb-3">
                                    <div class="card-body p-4 text-center">
                                        <div class="d-flex justify-content-center align-items-center mb-3 flex-wrap gap-3">
                                            <div>
                                                <div class="h5 fw-bold mb-1" id="checkInDate">กรุณาเลือกวันเช็คอิน</div>
                                                <div class="small text-muted">เช็คอิน</div>
                                            </div>
                                            <div class="h4 mx-3 text-warning">→</div>
                                            <div>
                                                <div class="h5 fw-bold mb-1" id="checkOutDate">กรุณาเลือกวันเช็คเอาท์
                                                </div>
                                                <div class="small text-muted">เช็คเอาท์</div>
                                            </div>
                                        </div>

                                        <button id="toggleCalendar" type="button"
                                            class="btn btn-warning px-4 py-2 fw-semibold">
                                            <i class="bi bi-calendar-date me-2"></i>เลือกวันจอง
                                        </button>
                                        <input type="hidden" name="booking_start" id="booking_start">
                                        <input type="hidden" name="booking_end" id="booking_end">

                                        <div class="md-4 text-start">
                                            <h6 class="fw-bold mb-2">หมายเหตุ:</h6>
                                            <!-- วันหยุดนักขัตฤกษ์ -->
                                            <div class="d-flex align-items-center md-6">
                                                <span class="d-inline-block rounded-circle me-2"
                                                    style="width: 16px; height: 16px; background-color: #fef08a;"></span>
                                                <span class="small">วันหยุดนักขัตฤกษ์</span>
                                            </div>
                                            <!-- วันที่มีการจองแล้ว -->
                                            <div class="d-flex align-items-center md-6">
                                                <span class="d-inline-block rounded-circle me-2"
                                                    style="width: 16px; height: 16px; background-color: #bfdbfe;"></span>
                                                <span class="small">วันที่มีการจองแล้ว
                                                    (สามารถจองได้หากช่วงเวลาไม่ซ้อนกัน)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Time Selection Box -->
                                <div class=" border-0 mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3">เวลาจอง</h5>
                                        <!-- Update the time inputs -->
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">เวลาเข้า</label>
                                                <input type="time" id="check_in_time" name="check_in_time"
                                                    step="60" min="08:00" max="22:59">
                                                <div class="form-text">เวลาเข้าต้องอยู่ระหว่าง 08:00 - 22:00 น.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">เวลาออก</label>
                                                <input type="time" id="check_out_time" name="check_out_time"
                                                    step="60" min="08:01" max="23:00">
                                                <div class="form-text">เวลาออกต้องอยู่ระหว่าง 09:00 - 23:00 น.</div>
                                            </div>
                                        </div>
                                        <p class="small text-muted mt-3 mb-0">
                                            <i class="bi bi-info-circle me-1"></i>
                                            หมายเหตุ: เวลาจองเริ่มตั้งแต่ 8:00 น. ถึง 23:00 น. ของแต่ละวัน
                                        </p>
                                    </div>
                                </div>
                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger px-4"
                                        onclick="window.location.href='{{ route('rooms.index') }}'">
                                        ยกเลิก
                                    </button>
                                    <button type="submit" class="btn btn-success px-4">
                                        ยืนยันการจอง
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Room Info and Booking Summary -->
                <div class="col-lg-4">
                    @include('partials.room-info')
                    @include('partials.booking-summary')
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Calendar customizations */
        .litepicker .day-item[data-tooltip] {
            position: relative;
        }

        .litepicker .day-item[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }

        /* Holiday styling */
        .litepicker .day-item.is-holiday {
            background-color: #fef08a !important;
            color: #854d0e !important;
            font-weight: bold;

        }

        /* Booked days styling */
        .litepicker .day-item.is-booked {
            background-color: #bfdbfe !important;
            color: #1e40af !important;

        }

        /* Selected dates */
        .litepicker .day-item.is-start-date,
        .litepicker .day-item.is-end-date {
            background-color: #FFC107 !important;
            color: #333 !important;
        }

        .litepicker .day-item.is-in-range {
            background-color: rgba(255, 193, 7, 0.2) !important;
            color: #333 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');

            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

                // ตรวจสอบการทับกันของเวลาก่อนยืนยัน
                const startDate = document.getElementById('booking_start').value.split('T')[0];
                const endDate = document.getElementById('booking_end').value.split('T')[0];
                const startTime = document.getElementById('check_in_time').value;
                const endTime = document.getElementById('check_out_time').value;

                // ใช้ฟังก์ชันตรวจสอบการทับกันจาก timeManager
                const conflicts = timeManager.checkTimeSlotConflict(startDate, startTime, endDate, endTime);

                if (conflicts.length > 0) {
                    // แสดงการแจ้งเตือนเมื่อเวลาทับกัน
                    const conflictInfo = conflicts.join('<br>');
                    Swal.fire({
                        title: 'ไม่สามารถจองได้!',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>เวลาที่เลือกทับกันกับการจองที่มีอยู่:</strong></p>
                                <div class="alert alert-danger">
                                    ${conflictInfo}
                                </div>
                                <p class="small text-muted mt-2">
                                    * กรุณาเปลี่ยนเวลาการจองให้ไม่ทับกันกับการจองที่มีอยู่
                                </p>
                            </div>
                        `,
                        icon: 'error',
                        confirmButtonText: 'เข้าใจแล้ว',
                        confirmButtonColor: '#d33'
                    });
                    return; // หยุดการทำงานถ้าเวลาทับกัน
                }

                // หากไม่มีการทับกัน ให้แสดง SweetAlert2 เพื่อยืนยันการจอง
                Swal.fire({
                    title: 'ยืนยันการจอง',
                    text: "คุณต้องการยืนยันการจองนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ยืนยัน!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งข้อมูลฟอร์มด้วย fetch
                        const formData = new FormData(bookingForm); // เก็บข้อมูลฟอร์ม

                        fetch(bookingForm.action, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (response.ok) {
                                    // แสดงข้อความสำเร็จ
                                    Swal.fire({
                                        title: 'จองสำเร็จ!',
                                        text: 'การจองของคุณได้รับการบันทึกเรียบร้อยแล้ว',
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง'
                                    }).then(() => {
                                        // หากส่งข้อมูลสำเร็จ ให้รีไดเร็กต์ไปที่หน้าหลัก
                                        window.location.href = '/';
                                    });
                                } else {
                                    // หากมีข้อผิดพลาด
                                    Swal.fire({
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                                        icon: 'error',
                                        confirmButtonText: 'ตกลง'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์',
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            });
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements - เลือกครั้งเดียวแล้วเก็บไว้ใช้
            const elements = {
                toggleButton: document.getElementById('toggleCalendar'),
                checkInDate: document.getElementById('checkInDate'),
                checkOutDate: document.getElementById('checkOutDate'),
                bookingStart: document.getElementById('booking_start'),
                bookingEnd: document.getElementById('booking_end'),
                totalDays: document.getElementById('totalDays'),
                bookingForm: document.getElementById('bookingForm'),
                checkInTime: document.getElementById('check_in_time'),
                checkOutTime: document.getElementById('check_out_time')
            };

            // Configuration - รวมข้อมูล config ไว้ที่เดียว (ลบส่วนราคาออก)
            const config = {
                holidaysWithNames: @json($holidaysWithNames),
                bookedDetails: @json($bookedDetails),
                disabledDays: @json($disabledDays),
                bookedTimeSlots: @json($bookedTimeSlots ?? [])
            };

            // Utility Functions
            const utils = {
                formatDate: (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },

                formatThaiDateWithTime: (date, timeStr) => {
                    const dateStr = date.toLocaleDateString('th-TH', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    return `${dateStr} (${timeStr} น.)`;
                },

                addHours: (time, hours) => {
                    const [h, m] = time.split(':').map(Number);
                    const newHour = (h + hours) % 24;
                    return `${String(newHour).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                },

                // คำนวณเวลารวมระหว่างวันที่และเวลา (ปรับปรุงให้แม่นยำถึงนาที)
                calculateTotalDuration: (startDate, startTime, endDate, endTime) => {
                    const start = new Date(`${startDate}T${startTime}:00`);
                    const end = new Date(`${endDate}T${endTime}:00`);
                    const diffMs = end.getTime() - start.getTime();

                    // คำนวณเป็นนาทีทั้งหมด
                    const totalMinutes = Math.floor(diffMs / (1000 * 60));

                    if (totalMinutes <= 0) {
                        return {
                            days: 0,
                            hours: 0,
                            minutes: 0,
                            totalMinutes: 0
                        };
                    }

                    const days = Math.floor(totalMinutes / (24 * 60));
                    const hours = Math.floor((totalMinutes % (24 * 60)) / 60);
                    const minutes = totalMinutes % 60;

                    return {
                        days: days,
                        hours: hours,
                        minutes: minutes,
                        totalMinutes: totalMinutes
                    };
                },

                // แปลงเวลาเป็นรูปแบบ "X วัน Y ชั่วโมง Z นาที"
                formatDurationDisplay: (duration) => {
                    const {
                        days,
                        hours,
                        minutes,
                        totalMinutes
                    } = duration;

                    if (totalMinutes <= 0) {
                        return 'กรุณาเลือกเวลา';
                    }

                    let result = [];

                    if (days > 0) {
                        result.push(`${days} วัน`);
                    }

                    if (hours > 0) {
                        result.push(`${hours} ชั่วโมง`);
                    }

                    if (minutes > 0) {
                        result.push(`${minutes} นาที`);
                    }

                    // ถ้าไม่มีอะไรเลย แสดงว่าเป็น 0 นาที
                    if (result.length === 0) {
                        return '0 นาที';
                    }

                    return result.join(' ');
                },

                // แปลงเวลาเป็นรูปแบบย่อ เช่น "2d 5h 30m"
                formatDurationShort: (duration) => {
                    const {
                        days,
                        hours,
                        minutes,
                        totalMinutes
                    } = duration;

                    if (totalMinutes <= 0) {
                        return '-';
                    }

                    let result = [];

                    if (days > 0) {
                        result.push(`${days}d`);
                    }

                    if (hours > 0) {
                        result.push(`${hours}h`);
                    }

                    if (minutes > 0) {
                        result.push(`${minutes}m`);
                    }

                    if (result.length === 0) {
                        return '0m';
                    }

                    return result.join(' ');
                },

                // คำนวณเวลาสำหรับแสดงผลแบบละเอียด
                getDetailedDurationInfo: (duration) => {
                    const {
                        days,
                        hours,
                        minutes,
                        totalMinutes
                    } = duration;

                    const totalHours = Math.floor(totalMinutes / 60);
                    const totalDays = Math.floor(totalMinutes / (24 * 60));

                    return {
                        ...duration,
                        totalHours: totalHours,
                        totalDays: totalDays,
                        formatted: utils.formatDurationDisplay(duration),
                        formattedShort: utils.formatDurationShort(duration)
                    };
                },

                showAlert: (title, text, icon = 'warning') => {
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        confirmButtonText: 'ตกลง'
                    });
                },

                showBookingAlert: (bookedInfo) => {
                    Swal.fire({
                        title: 'ห้องนี้มีการจองแล้ว!',
                        html: `
                    <div class="text-start">
                        <p class="mb-2"><strong>ช่วงเวลาที่มีการจอง:</strong></p>
                        <div class="alert alert-warning">
                            ${bookedInfo}
                        </div>
                        <p class="small text-muted mt-2">
                            * กรุณาเลือกช่วงเวลาอื่นที่ไม่ซ้อนกับการจองที่มีอยู่
                        </p>
                    </div>
                `,
                        icon: 'warning',
                        confirmButtonText: 'เข้าใจแล้ว',
                        confirmButtonColor: '#f39c12'
                    });
                }
            };

            // Time Management Functions
            const timeManager = {
                generateAvailableTimeSlots: (bookedSlots) => {
                    const allSlots = [];
                    let currentTime = '08:00';

                    while (currentTime <= '22:00') {
                        const endTime = utils.addHours(currentTime, 1);
                        const isAvailable = !timeManager.isTimeSlotBooked(currentTime, endTime,
                            bookedSlots);

                        if (isAvailable) {
                            allSlots.push({
                                start: currentTime,
                                end: endTime
                            });
                        }
                        currentTime = endTime;
                    }
                    return allSlots;
                },

                isTimeSlotBooked: (start, end, bookedSlots) => {
                    return bookedSlots.some(booking => {
                        return (start < booking.end && end > booking.start);
                    });
                },

                // ตรวจสอบว่าช่วงเวลาที่เลือกซ้อนกับการจองที่มีอยู่หรือไม่
                checkTimeSlotConflict: (startDate, startTime, endDate, endTime) => {
                    const conflicts = [];
                    const currentDate = new Date(startDate);
                    const finalDate = new Date(endDate);

                    while (currentDate <= finalDate) {
                        const dateStr = utils.formatDate(currentDate);
                        const dayBookings = config.bookedTimeSlots[dateStr] || [];

                        dayBookings.forEach(booking => {
                            // ตรวจสอบการซ้อนของเวลา
                            let checkStart = startTime;
                            let checkEnd = endTime;

                            // ถ้าเป็นวันเดียวกัน ใช้เวลาที่กำหนด
                            if (dateStr === startDate && dateStr === endDate) {
                                // วันเดียวกัน
                            } else if (dateStr === startDate) {
                                // วันแรก - จากเวลาเริ่มถึง 23:00
                                checkEnd = '23:00';
                            } else if (dateStr === endDate) {
                                // วันสุดท้าย - จาก 08:00 ถึงเวลาจบ
                                checkStart = '08:00';
                            } else {
                                // วันระหว่าง - ทั้งวัน
                                checkStart = '08:00';
                                checkEnd = '23:00';
                            }

                            // ตรวจสอบการซ้อน
                            if (checkStart < booking.end && checkEnd > booking.start) {
                                const conflictDate = currentDate.toLocaleDateString('th-TH', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                });
                                conflicts.push(
                                    `${conflictDate}: ${booking.start} - ${booking.end} น.`);
                            }
                        });

                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    return conflicts;
                },

                updateAvailableTimeSlots: (selectedDate) => {
                    const dateBookings = config.bookedTimeSlots[selectedDate] || [];

                    // Reset time inputs to normal input type
                    if (elements.checkInTime.tagName === 'SELECT') {
                        const checkInInput = document.createElement('input');
                        checkInInput.type = 'time';
                        checkInInput.id = 'check_in_time';
                        checkInInput.name = 'check_in_time';
                        checkInInput.step = '60';
                        checkInInput.min = '08:00';
                        checkInInput.max = '22:59';
                        checkInInput.className = elements.checkInTime.className;
                        elements.checkInTime.parentNode.replaceChild(checkInInput, elements.checkInTime);
                        elements.checkInTime = checkInInput;
                    }

                    if (elements.checkOutTime.tagName === 'SELECT') {
                        const checkOutInput = document.createElement('input');
                        checkOutInput.type = 'time';
                        checkOutInput.id = 'check_out_time';
                        checkOutInput.name = 'check_out_time';
                        checkOutInput.step = '60';
                        checkOutInput.min = '08:01';
                        checkOutInput.max = '23:00';
                        checkOutInput.className = elements.checkOutTime.className;
                        elements.checkOutTime.parentNode.replaceChild(checkOutInput, elements.checkOutTime);
                        elements.checkOutTime = checkOutInput;
                    }

                    // Clear values
                    elements.checkInTime.value = '';
                    elements.checkOutTime.value = '';
                },

                updateCheckInOutDisplay: () => {
                    const checkInSpan = document.getElementById('checkInTime');
                    const checkOutSpan = document.getElementById('checkOutTime');

                    if (checkInSpan && checkOutSpan) {
                        checkInSpan.innerText = elements.checkInTime.value ?
                            `${elements.checkInTime.value} น.` : '-';
                        checkOutSpan.innerText = elements.checkOutTime.value ?
                            `${elements.checkOutTime.value} น.` : '-';
                    }
                }
            };

            // Duration Calculator (ปรับปรุงให้คำนวณแม่นยำถึงนาที)
            const durationCalculator = {
                updateDuration: () => {
                    const startDate = elements.bookingStart.value;
                    const endDate = elements.bookingEnd.value;
                    const startTime = elements.checkInTime.value;
                    const endTime = elements.checkOutTime.value;

                    if (!startDate || !endDate || !startTime || !endTime) {
                        if (elements.totalDays) {
                            elements.totalDays.innerText = '-';
                            elements.totalDays.title = 'กรุณาเลือกวันที่และเวลา';
                        }
                        return;
                    }

                    const duration = utils.calculateTotalDuration(startDate, startTime, endDate, endTime);
                    const detailedInfo = utils.getDetailedDurationInfo(duration);

                    if (elements.totalDays) {
                        elements.totalDays.innerText = detailedInfo.formatted;

                        // เพิ่ม tooltip แสดงข้อมูลเพิ่มเติม
                        elements.totalDays.title =
                            `รวม: ${detailedInfo.totalMinutes} นาที (${detailedInfo.totalHours} ชั่วโมง)`;

                        // เพิ่ม class สำหรับการแสดงผลที่แตกต่างกัน
                        elements.totalDays.className = 'duration-display';

                        // ถ้าต้องการแสดงข้อมูลเพิ่มเติมใน element อื่น
                        const detailElement = document.getElementById('durationDetail');
                        if (detailElement) {
                            detailElement.innerHTML = `
                                <small class="text-muted">
                                    รวม ${detailedInfo.totalMinutes} นาที
                                    (${detailedInfo.totalHours} ชั่วโมง)
                                </small>
                            `;
                        }
                    }

                    // Log สำหรับ debugging
                    console.log('Duration calculated:', {
                        input: {
                            startDate,
                            startTime,
                            endDate,
                            endTime
                        },
                        result: detailedInfo
                    });
                },

                // ฟังก์ชันสำหรับการแสดงผลแบบละเอียด
                showDetailedDuration: () => {
                    const startDate = elements.bookingStart.value;
                    const endDate = elements.bookingEnd.value;
                    const startTime = elements.checkInTime.value;
                    const endTime = elements.checkOutTime.value;

                    if (!startDate || !endDate || !startTime || !endTime) {
                        return null;
                    }

                    const duration = utils.calculateTotalDuration(startDate, startTime, endDate, endTime);
                    return utils.getDetailedDurationInfo(duration);
                }
            };

            // Date Display Manager
            const dateDisplayManager = {
                currentSelectedDates: {
                    start: null,
                    end: null
                },

                updateDateDisplay: (startDate, endDate = null) => {
                    dateDisplayManager.currentSelectedDates.start = startDate;
                    dateDisplayManager.currentSelectedDates.end = endDate;
                    dateDisplayManager.refreshDisplay();
                },

                refreshDisplay: () => {
                    const {
                        start,
                        end
                    } = dateDisplayManager.currentSelectedDates;
                    if (!start) return;

                    const checkInTime = elements.checkInTime.value;
                    const checkOutTime = elements.checkOutTime.value;

                    if (checkInTime && checkOutTime) {
                        elements.checkInDate.innerText = utils.formatThaiDateWithTime(start, checkInTime);
                        elements.checkOutDate.innerText = utils.formatThaiDateWithTime(end || start,
                            checkOutTime);
                    } else {
                        const startDateStr = start.toLocaleDateString('th-TH', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                        const endDateStr = (end || start).toLocaleDateString('th-TH', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });

                        elements.checkInDate.innerText = `${startDateStr} (รอเลือกเวลา)`;
                        elements.checkOutDate.innerText = `${endDateStr} (รอเลือกเวลา)`;
                    }
                }
            };

            // Form Validation (ปรับปรุงการตรวจสอบ)
            const validator = {
                validateDateSelection: () => {
                    if (!elements.bookingStart.value || !elements.bookingEnd.value) {
                        utils.showAlert('วันที่', 'กรุณาเลือกวันที่จอง');
                        return false;
                    }
                    return true;
                },

                validateTimeSelection: () => {
                    const checkIn = elements.checkInTime.value;
                    const checkOut = elements.checkOutTime.value;

                    if (!checkIn || !checkOut) {
                        utils.showAlert('เวลา', 'กรุณาเลือกเวลาเข้าและเวลาออก');
                        return false;
                    }

                    // ตรวจสอบว่าเวลาออกมาหลังเวลาเข้า
                    const startDate = elements.bookingStart.value;
                    const endDate = elements.bookingEnd.value;
                    const duration = utils.calculateTotalDuration(startDate, checkIn, endDate, checkOut);

                    if (duration.totalMinutes <= 0) {
                        utils.showAlert('เวลาไม่ถูกต้อง', 'เวลาออกต้องมาหลังเวลาเข้า');
                        return false;
                    }

                    // ตรวจสอบระยะเวลาขั้นต่ำ (ถ้าต้องการ)
                    if (duration.totalMinutes < 60) {
                        const confirmResult = confirm(
                            'ระยะเวลาการจองน้อยกว่า 1 ชั่วโมง ต้องการดำเนินการต่อหรือไม่?');
                        if (!confirmResult) {
                            return false;
                        }
                    }

                    return true;
                }
            };

            // ทำให้ timeManager เป็น global variable เพื่อให้สคริปต์แรกเข้าถึงได้
            window.timeManager = timeManager;
            window.durationCalculator = durationCalculator; // เพิ่มการเข้าถึง durationCalculator

            // Calendar Setup
            const holidays = Object.keys(config.holidaysWithNames);
            const bookedDays = Object.keys(config.bookedDetails);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const picker = new Litepicker({
                element: elements.toggleButton,
                singleMode: false,
                numberOfMonths: 2,
                numberOfColumns: 2,
                format: 'D MMM YYYY',
                lang: "th-TH",
                autoApply: true,
                minDate: today,
                allowSingleDayRange: true,
                tooltipText: {
                    one: '1 วัน',
                    other: 'วัน'
                },
                setup: (picker) => {
                    picker.on('render', () => {
                        document.querySelectorAll('.container__days .day-item').forEach(day => {
                            const date = day.getAttribute('data-time');
                            if (date) {
                                const dateObj = new Date(parseInt(date));
                                const formattedDate = utils.formatDate(dateObj);

                                if (holidays.includes(formattedDate)) {
                                    day.classList.add('is-holiday');
                                    day.setAttribute('data-tooltip', config
                                        .holidaysWithNames[formattedDate]);
                                }

                                if (bookedDays.includes(formattedDate)) {
                                    day.classList.add('is-booked');
                                    day.setAttribute('data-tooltip', config
                                        .bookedDetails[formattedDate]);
                                }
                            }
                        });
                    });

                    picker.on('selected', (date1, date2) => {
                        const realDate1 = date1.dateInstance;
                        const realDate2 = date2 ? date2.dateInstance : null;

                        if (!(realDate1 instanceof Date) || isNaN(realDate1.getTime())) return;
                        if (realDate2 && (!(realDate2 instanceof Date) || isNaN(realDate2
                                .getTime()))) return;

                        // Update booking dates
                        elements.bookingStart.value = utils.formatDate(realDate1);
                        elements.bookingEnd.value = realDate2 ? utils.formatDate(realDate2) :
                            utils.formatDate(realDate1);

                        // Update available time slots for start date
                        timeManager.updateAvailableTimeSlots(utils.formatDate(realDate1));

                        // Update display
                        dateDisplayManager.updateDateDisplay(realDate1, realDate2);

                        // Reset duration display
                        durationCalculator.updateDuration();
                    });
                }
            });

            // Event Listeners Setup
            const setupEventListeners = () => {
                // Calendar toggle
                if (elements.toggleButton) {
                    elements.toggleButton.addEventListener('click', () => picker.show());
                }

                // Check-in time change
                if (elements.checkInTime) {
                    elements.checkInTime.addEventListener('change', function() {
                        timeManager.updateCheckInOutDisplay();
                        dateDisplayManager.refreshDisplay();
                        durationCalculator.updateDuration();
                    });
                }
                // Check-out time change
                if (elements.checkOutTime) {
                    elements.checkOutTime.addEventListener('change', function() {
                        timeManager.updateCheckInOutDisplay();
                        dateDisplayManager.refreshDisplay();
                        durationCalculator.updateDuration();
                    });
                }
                // Form submission
                if (elements.bookingForm) {
                    elements.bookingForm.addEventListener('submit', function(e) {
                        if (!validator.validateDateSelection() || !validator.validateTimeSelection()) {
                            e.preventDefault();
                            return false;
                        }
                        // Set final datetime values
                        elements.bookingStart.value =
                            `${elements.bookingStart.value}T${elements.checkInTime.value}:00`;
                        elements.bookingEnd.value =
                            `${elements.bookingEnd.value}T${elements.checkOutTime.value}:00`;
                    });
                }
            };
            // Initialize
            setupEventListeners();
            timeManager.updateCheckInOutDisplay();
            durationCalculator.updateDuration(); // เรียกใช้การคำนวณครั้งแรก

            // Load existing dates if available
            if (elements.bookingStart && elements.bookingEnd && elements.bookingStart.value && elements.bookingEnd
                .value) {
                let startParts = elements.bookingStart.value.split('T')[0];
                let endParts = elements.bookingEnd.value.split('T')[0];
                let startDate = new Date(startParts + 'T00:00:00');
                let endDate = new Date(endParts + 'T00:00:00');

                picker.setDateRange(startDate, endDate);
                picker.render();
            }
        });
    </script>
@endsection
