@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">

            <!-- Hero Banner -->
            <div class="card bg-warning text-white mb-4">
                <div class="card-body text-center py-5">
                    <h1 class="display-4 fw-bold">ระบบจองห้องออนไลน์</h1>
                    <h2>มหาวิทยาลัยราชภัฏสกลนคร</h2>
                    <p class="lead mt-3">บริการจองห้องเรียน ห้องประชุม และสถานที่จัดกิจกรรมต่างๆ แบบออนไลน์</p>
                    <a href="{{ route('booking.index') }}" class="btn btn-warning btn-lg mt-3">
                        <i class="fas fa-calendar-plus me-2"></i>จองห้องเลย
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalBuildings }} อาคาร</h3>
                            <p>อาคารที่ให้บริการจองห้อง</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open text-success display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalRooms }} ห้อง</h3>
                            <p>ห้องที่เปิดให้จอง</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check text-warning display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalBookings }} การจองทั้งหมด</h3>
                            <p>การจองทั้งหมดที่มีในระบบ</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- การจองของฉัน -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-3">การจองของฉัน {{ $totalmyBookings }} รายการ</h3>
                    <a href="{{ route('my-bookings') }}" class="text-warning">
                        <strong>ดูรายการจองทั้งหมด</strong>
                    </a>
                </div>
                @if (isset($myBookings) && $myBookings->count() > 0)
                    <div class="booking-carousel d-flex overflow-auto pb-3">
                        @foreach ($myBookings as $booking)
                            <div class="card me-3 flex-shrink-0" style="width: 300px;">
                                @if (!empty($booking->room) && !empty($booking->room->image))
                                    <img src="{{ asset('storage/' . $booking->room->image) }}"
                                        alt="{{ $booking->room->room_name ?? 'Room Image' }}"
                                        class="img-fluid rounded-lg shadow-sm" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5"
                                        style="height: 180px;">
                                        <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                    </div>
                                @endif
                                <div class="card-body p-3">
                                    <h5 class="card-title">{{ $booking->room_name ?? '-' }}</h5>
                                    <p class="card-text text-muted">
                                    <div><strong>จองเมื่อ:</strong>
                                        {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}
                                    </div>
                                    <div><strong>อาคาร:</strong> {{ $booking->building_name ?? '-' }} </div>
                                    <div><strong>เริ่มวันที่:</strong>
                                        {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                        น.</div>
                                    <div><strong>ถึงวันที่:</strong>
                                        {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                        น.</div>
                                    <div><strong>สถานะ:</strong>
                                        <span
                                            class="badge bg-{{ $booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                            {{ $booking->status->status_name ?? '-' }}
                                        </span>
                                    </div>
                                    </p>
                                    <div class='items-center mt-4'><button type="button"
                                            class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailsModal{{ $booking->id }}">
                                            <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                        </button> @include('booking-status.modal')</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">คุณยังไม่มีการจองห้อง</p>
                @endif
            </div>
            <!-- Featured Rooms -->
            <h3 class="fw-bold mb-3">ห้องแนะนำ</h3>
            <div class="row g-4 mb-4">
                @if (isset($featuredRooms) && count($featuredRooms) > 0)
                    @foreach ($featuredRooms as $room)
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="{{ $room->image ? asset('storage/room_images' . $room->image) : '/api/placeholder/400/200' }}"
                                    class="card-img-top" alt="Room Image">
                                <div class="card-body">
                                    <h5 class="fw-bold">{{ $room->room_name }}</h5>
                                    <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น
                                        {{ $room->floor }}</p>
                                    <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                                    <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /วัน</p>
                                    <a href="{{ route('partials.booking.form', ['id' => $room->room_id]) }}"
                                        class="btn btn-warning w-100">จองเลย</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>ไม่มีห้องแนะนำในขณะนี้</p>
                    </div>
                @endif
            </div>

            <!-- How to Book Section -->
            <div class="card bg-light mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 text-center">วิธีการจองห้อง</h3>
                    <div class="row text-center g-4">
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                                <h4>1. เลือกห้อง</h4>
                                <p>ค้นหาและเลือกห้องที่ต้องการจองตามวัตถุประสงค์การใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-success text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                                <h4>2. เลือกวันเวลา</h4>
                                <p>เลือกวันและเวลาที่ต้องการใช้งานห้อง</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-warning text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-clipboard-list fa-2x"></i>
                                </div>
                                <h4>3. กรอกข้อมูล</h4>
                                <p>กรอกข้อมูลการจองและรายละเอียดการใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-danger text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h4>4. รอการยืนยัน</h4>
                                <p>รอการยืนยันการจองผ่านอีเมลหรือตรวจสอบในระบบ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & FAQ -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">ติดต่อเรา</h4>
                        </div>
                        <div class="card-body ps-3">
                            <p><i class="fas fa-university me-2 ms-1 text-primary"></i>มหาวิทยาลัยราชภัฏสกลนคร</p>
                            <p><i class="fas fa-map-marker-alt me-2 ms-1 text-danger"></i>680 ถนนนิตโย ตำบลธาตุเชิงชุม
                                อำเภอเมือง จังหวัดสกลนคร 47000</p>
                            <p><i class="fas fa-phone-alt me-2 ms-1 text-success"></i>042-970021</p>
                            <p><i class="fas fa-envelope me-2 ms-1 text-warning"></i>booking@snru.ac.th</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">คำถามที่พบบ่อย</h4>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed border-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq1">
                                            ทำการจองห้องได้ล่วงหน้ากี่วัน?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถทำการจองล่วงหน้าได้ไม่เกิน 30 วัน
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed border-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2">
                                            มีค่าใช้จ่ายในการจองห้องหรือไม่?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            ค่าใช้จ่ายขึ้นอยู่กับประเภทห้องและระยะเวลาการใช้งาน
                                            โปรดตรวจสอบราคาในหน้ารายละเอียดห้อง
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed border-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq3">
                                            สามารถยกเลิกการจองได้หรือไม่?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถยกเลิกการจองได้ผ่านระบบออนไลน์ โดยต้องยกเลิกก่อนวันใช้งานอย่างน้อย 24
                                            ชั่วโมง
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed border-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq4">
                                            ต้องชำระเงินอย่างไร?
                                        </button>
                                    </h2>
                                    <div id="faq4" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถชำระเงินผ่านบัตรเครดิต/เดบิต
                                            หรือชำระเงินสดที่สำนักงานการเงินของมหาวิทยาลัย
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed border-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq5">
                                            มีห้องให้เลือกกี่ประเภท?
                                        </button>
                                    </h2>
                                    <div id="faq5" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            มีห้องให้เลือกหลากหลายประเภท เช่น ห้องเรียนขนาดเล็ก ห้องประชุม ห้องสัมมนา
                                            และห้องอเนกประสงค์
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
