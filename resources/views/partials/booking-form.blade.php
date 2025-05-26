@extends('layouts.app')

@section('content')
    <!-- Main Container with Background -->
    <div class="container-fluid py-5"
        style="background-image: url('{{ asset('images/bg-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="container">
            <div class="row">
                <!-- Booking Form Section -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow rounded-lg border-0 mb-4">
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
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">รายละเอียดเพิ่มเติม(ถ้ามี)</label>
                                        <textarea name="booker_info" class="form-control" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม"></textarea>
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
                                <div class="card border-0 shadow-sm mt-4 mb-3">
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
                                <div class="card border-0 shadow-sm mb-4">
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
                                    <button type="button" class="btn btn-danger px-4">
                                        <i class="bi bi-x-circle me-1"></i>ยกเลิก
                                    </button>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="bi bi-check-circle me-1"></i>ยืนยันการจอง
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
        /* Font family */
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f5f5f7;
            color: #333;
        }

        /* Card styling */
        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Button styling */
        .btn-success {
            background-color: #FFC107;
            border-color: #FFC107;
            color: #333;
        }

        .btn-success:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            color: #333;
        }

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
            cursor: not-allowed !important;
        }

        /* Booked days styling */
        .litepicker .day-item.is-booked {
            background-color: #bfdbfe !important;
            color: #1e40af !important;
            cursor: not-allowed !important;
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

                // แสดง SweetAlert2 เพื่อยืนยันการจอง
                Swal.fire({
                    title: 'ยืนยันการจอง',
                    text: "คุณต้องการยืนยันการจองนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ยืนยัน!'
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
                                    // หากส่งข้อมูลสำเร็จ ให้รีไดเร็กต์ไปที่หน้าหลัก
                                    window.location.href = '/';
                                } else {
                                    // หากมีข้อผิดพลาด
                                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้',
                                        'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์',
                                    'error');
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
                serviceFee: document.getElementById('serviceFee'),
                totalPrice: document.getElementById('totalPrice'),
                bankPaymentCheckbox: document.getElementById('bankPaymentCheckbox'),
                bankPaymentDetails: document.getElementById('bankPaymentDetails'),
                paymentSlip: document.getElementById('paymentSlip'),
                fileName: document.getElementById('fileName'),
                bookingForm: document.getElementById('bookingForm'),
                checkInTime: document.getElementById('check_in_time'),
                checkOutTime: document.getElementById('check_out_time')
            };

            // Configuration - รวมข้อมูล config ไว้ที่เดียว
            const config = {
                serviceRate: parseFloat({{ $room->service_rates ?? 0 }}), // ค่าบริการต่อชั่วโมง
                holidaysWithNames: @json($holidaysWithNames),
                bookedDetails: @json($bookedDetails),
                disabledDays: @json($disabledDays),
                bookedTimeSlots: @json($bookedTimeSlots ?? [])
            };

            // Validation
            if (isNaN(config.serviceRate)) {
                console.error('serviceRate is not a valid number');
                return;
            }

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

                numberWithCommas: (x) => {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },

                addHours: (time, hours) => {
                    const [h, m] = time.split(':').map(Number);
                    const newHour = (h + hours) % 24;
                    return `${String(newHour).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                },

                // คำนวณชั่วโมงรวมระหว่างวันที่และเวลา
                calculateTotalHours: (startDate, startTime, endDate, endTime) => {
                    const start = new Date(`${startDate}T${startTime}:00`);
                    const end = new Date(`${endDate}T${endTime}:00`);

                    const diffMs = end.getTime() - start.getTime();
                    const diffHours = Math.ceil(diffMs / (1000 * 60 * 60)); // ปัดขึ้นเป็นชั่วโมง

                    return Math.max(1, diffHours); // อย่างน้อย 1 ชั่วโมง
                },

                // แปลงชั่วโมงเป็นรูปแบบ "X วัน Y ชั่วโมง"
                formatDurationDisplay: (totalHours) => {
                    if (totalHours < 24) {
                        return `${totalHours} ชั่วโมง`;
                    } else {
                        const days = Math.floor(totalHours / 24);
                        const hours = totalHours % 24;

                        if (hours === 0) {
                            return `${days} วัน`;
                        } else {
                            return `${days} วัน ${hours} ชั่วโมง`;
                        }
                    }
                },

                showAlert: (title, text) => {
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        confirmButtonText: 'ตกลง'
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

                updateAvailableTimeSlots: (selectedDate) => {
                    const dateBookings = config.bookedTimeSlots[selectedDate] || [];

                    // Reset time inputs
                    elements.checkInTime.innerHTML = '<option value="">เลือกเวลาเข้า</option>';
                    elements.checkOutTime.innerHTML = '<option value="">เลือกเวลาออก</option>';

                    // Generate and populate available time slots
                    const timeSlots = timeManager.generateAvailableTimeSlots(dateBookings);
                    timeSlots.forEach(slot => {
                        if (slot.end < '23:00') {
                            const option = document.createElement('option');
                            option.value = slot.start;
                            option.textContent = `${slot.start} น.`;
                            elements.checkInTime.appendChild(option);
                        }
                    });
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
                },

                // ฟังก์ชันใหม่: สร้างตัวเลือกเวลาออกที่ครอบคลุมหลายวัน
                generateCheckOutOptions: (startDate, startTime, endDate) => {
                    const options = [];
                    const currentDate = new Date(startDate);
                    const finalDate = new Date(endDate);

                    while (currentDate <= finalDate) {
                        const dateStr = utils.formatDate(currentDate);
                        const dateBookings = config.bookedTimeSlots[dateStr] || [];
                        const availableSlots = timeManager.generateAvailableTimeSlots(dateBookings);

                        availableSlots.forEach(slot => {
                            // ถ้าเป็นวันเดียวกับวันเริ่ม ให้เลือกเวลาหลังจากเวลาเริ่มเท่านั้น
                            if (dateStr === startDate && slot.start <= startTime) {
                                return;
                            }

                            // ถ้าเป็นวันสุดท้าย ให้เลือกเวลาก่อน 23:00
                            if (dateStr === utils.formatDate(finalDate) && slot.start >= '23:00') {
                                return;
                            }

                            const displayDate = currentDate.toLocaleDateString('th-TH', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });

                            options.push({
                                value: `${dateStr}T${slot.start}`,
                                text: `${displayDate} ${slot.start} น.`,
                                date: dateStr,
                                time: slot.start
                            });
                        });

                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    return options;
                }
            };

            // Price Calculator - แก้ไขให้คำนวณตามชั่วโมง
            const priceCalculator = {
                updatePricing: () => {
                    const startDate = elements.bookingStart.value;
                    const endDate = elements.bookingEnd.value;
                    const startTime = elements.checkInTime.value;
                    const endTime = elements.checkOutTime.value;

                    if (!startDate || !endDate || !startTime || !endTime) {
                        elements.totalDays.innerText = '-';
                        elements.serviceFee.innerText = '-';
                        elements.totalPrice.innerText = '-';
                        return;
                    }

                    // แยกเวลาออกจาก endTime ถ้ามีรูปแบบ "dateT time"
                    let actualEndDate = endDate;
                    let actualEndTime = endTime;

                    if (endTime.includes('T')) {
                        const [date, time] = endTime.split('T');
                        actualEndDate = date;
                        actualEndTime = time;
                    }

                    const totalHours = utils.calculateTotalHours(startDate, startTime, actualEndDate,
                        actualEndTime);
                    const totalServiceFee = totalHours * config.serviceRate;
                    const formattedPrice = utils.numberWithCommas(totalServiceFee.toFixed(2)) + ' บาท';
                    const durationText = utils.formatDurationDisplay(totalHours);

                    elements.totalDays.innerText = durationText;
                    elements.serviceFee.innerText = formattedPrice;
                    elements.totalPrice.innerText = formattedPrice;
                }
            };

            // Date Display Manager - แก้ไขให้แสดงข้อมูลชั่วโมง
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
                        // แยกเวลาออกถ้ามีรูปแบบ "dateT time"
                        let displayEndDate = end || start;
                        let displayEndTime = checkOutTime;

                        if (checkOutTime.includes('T')) {
                            const [date, time] = checkOutTime.split('T');
                            displayEndDate = new Date(date);
                            displayEndTime = time;
                        }

                        elements.checkInDate.innerText = utils.formatThaiDateWithTime(start, checkInTime);
                        elements.checkOutDate.innerText = utils.formatThaiDateWithTime(displayEndDate,
                            displayEndTime);
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

            // Form Validation - แก้ไขให้ตรวจสอบเวลาข้ามวัน
            const validator = {
                validateDateSelection: () => {
                    if (!elements.bookingStart.value || !elements.bookingEnd.value) {
                        alert('กรุณาเลือกวันที่จอง');
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

                    // ตรวจสอบว่าเวลาออกมาหลังเวลาเข้า (รองรับข้ามวัน)
                    const startDate = elements.bookingStart.value;
                    let endDate = elements.bookingEnd.value;
                    let endTime = checkOut;

                    if (checkOut.includes('T')) {
                        const [date, time] = checkOut.split('T');
                        endDate = date;
                        endTime = time;
                    }

                    const startDateTime = new Date(`${startDate}T${checkIn}:00`);
                    const endDateTime = new Date(`${endDate}T${endTime}:00`);

                    if (endDateTime <= startDateTime) {
                        utils.showAlert('เวลาไม่ถูกต้อง', 'เวลาออกต้องมาหลังเวลาเข้า');
                        return false;
                    }

                    return true;
                },

                validatePaymentSlip: () => {
                    if (elements.bankPaymentCheckbox.checked && !elements.paymentSlip.files[0]) {
                        alert('กรุณาอัปโหลดสลิปการโอนเงิน');
                        return false;
                    }
                    return true;
                }
            };

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

                        // Reset pricing display
                        priceCalculator.updatePricing();
                    });
                }
            });

            // Event Listeners
            const setupEventListeners = () => {
                // Calendar toggle
                elements.toggleButton.addEventListener('click', () => picker.show());

                // Bank payment toggle
                elements.bankPaymentCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        elements.bankPaymentDetails.classList.remove('d-none');
                    } else {
                        elements.bankPaymentDetails.classList.add('d-none');
                    }
                });

                // File upload display
                elements.paymentSlip.addEventListener('change', function() {
                    elements.fileName.innerText = this.files[0] ? this.files[0].name :
                        "ยังไม่ได้เลือกไฟล์";
                });

                // Check-in time change - แก้ไขให้สร้างตัวเลือกเวลาออกข้ามวัน
                elements.checkInTime.addEventListener('change', function() {
                    const checkInValue = this.value;
                    const startDate = elements.bookingStart.value;
                    const endDate = elements.bookingEnd.value;

                    if (!checkInValue || !startDate || !endDate) return;

                    // สร้างตัวเลือกเวลาออกที่ครอบคลุมหลายวัน
                    const checkOutOptions = timeManager.generateCheckOutOptions(startDate, checkInValue,
                        endDate);

                    // Reset check-out options
                    elements.checkOutTime.innerHTML = '<option value="">เลือกเวลาออก</option>';

                    // Add available check-out times
                    checkOutOptions.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.value;
                        optionElement.textContent = option.text;
                        elements.checkOutTime.appendChild(optionElement);
                    });

                    elements.checkOutTime.disabled = false;

                    // Update display
                    timeManager.updateCheckInOutDisplay();
                    dateDisplayManager.refreshDisplay();
                    priceCalculator.updatePricing();
                });

                // Check-out time change - แก้ไขให้รองรับรูปแบบใหม่
                elements.checkOutTime.addEventListener('change', function() {
                    timeManager.updateCheckInOutDisplay();
                    dateDisplayManager.refreshDisplay();
                    priceCalculator.updatePricing();
                });

                // Form submission
                elements.bookingForm.addEventListener('submit', function(e) {
                    if (!validator.validateDateSelection() ||
                        !validator.validateTimeSelection() ||
                        !validator.validatePaymentSlip()) {
                        e.preventDefault();
                        return;
                    }

                    // Process end date and time
                    let finalEndDate = elements.bookingEnd.value;
                    let finalEndTime = elements.checkOutTime.value;

                    if (elements.checkOutTime.value.includes('T')) {
                        const [date, time] = elements.checkOutTime.value.split('T');
                        finalEndDate = date;
                        finalEndTime = time;
                    }

                    // Set final values
                    elements.bookingStart.value =
                        `${elements.bookingStart.value}T${elements.checkInTime.value}:00`;
                    elements.bookingEnd.value = `${finalEndDate}T${finalEndTime}:00`;
                });
            };

            // Initialize
            setupEventListeners();
            timeManager.updateCheckInOutDisplay();

            // Load existing dates if available
            if (elements.bookingStart.value && elements.bookingEnd.value) {
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
