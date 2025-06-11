@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- How-to-use Section -->
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <h3 class="fw-bold text-warning mb-4 mt-4 text-center">
                    ขั้นตอนการใช้งานระบบจองห้อง
                </h3>
                <div class="row g-4">
                    @php
                        $steps = [
                            [
                                'icon' => 'fas fa-search',
                                'title' => 'ค้นหาห้อง',
                                'desc' => 'ใช้ช่องค้นหาหรือเลือกประเภทห้องและอาคารเพื่อค้นหาห้องที่คุณต้องการจอง',
                            ],
                            [
                                'icon' => 'fas fa-calendar-alt',
                                'title' => 'ตรวจสอบวันว่าง',
                                'desc' => 'ตรวจสอบวันที่และเวลาที่ห้องว่างเพื่อเลือกเวลาที่เหมาะสมสำหรับการจอง',
                            ],
                            [
                                'icon' => 'fas fa-check-circle',
                                'title' => 'ยืนยันการจอง',
                                'desc' => 'กรอกข้อมูลการจองและยืนยันการจองห้อง พร้อมรับส่วนลดพิเศษสำหรับการจองครั้งแรก',
                            ],
                            [
                                'icon' => 'fas fa-file-alt',
                                'title' => 'รับเอกสารยืนยัน',
                                'desc' => 'หลังจากจองสำเร็จ ระบบจะส่งเอกสารยืนยันการจองไปยังอีเมลของคุณ',
                            ],
                        ];
                    @endphp

                    @foreach ($steps as $index => $step)
                        <div class="col-md-6">
                            <div class="card bg-light border-0 p-4 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="width: 50px; height: 50px;">
                                        <i class="{{ $step['icon'] }} fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">ขั้นตอนที่ {{ $index + 1 }}: {{ $step['title'] }}</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="card shadow-sm mb-5">
            <div x-data="{ openFaq: null }">
                <div class="card-body">
                    <h3 class="fw-bold text-warning mb-4 mt-4 text-center">
                        คำถามที่พบบ่อย (FAQ)
                    </h3>
                    @include('components.qna')
                </div>
            </div>
        </div>
    @endsection
