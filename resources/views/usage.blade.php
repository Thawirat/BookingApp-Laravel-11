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
                        ['icon' => 'fas fa-search', 'title' => 'ค้นหาห้อง', 'desc' => 'ใช้ช่องค้นหาหรือเลือกประเภทห้องและอาคารเพื่อค้นหาห้องที่คุณต้องการจอง'],
                        ['icon' => 'fas fa-calendar-alt', 'title' => 'ตรวจสอบวันว่าง', 'desc' => 'ตรวจสอบวันที่และเวลาที่ห้องว่างเพื่อเลือกเวลาที่เหมาะสมสำหรับการจอง'],
                        ['icon' => 'fas fa-check-circle', 'title' => 'ยืนยันการจอง', 'desc' => 'กรอกข้อมูลการจองและยืนยันการจองห้อง พร้อมรับส่วนลดพิเศษสำหรับการจองครั้งแรก'],
                        ['icon' => 'fas fa-file-alt', 'title' => 'รับเอกสารยืนยัน', 'desc' => 'หลังจากจองสำเร็จ ระบบจะส่งเอกสารยืนยันการจองไปยังอีเมลของคุณ'],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                <div class="col-md-6">
                    <div class="card bg-light border-0 p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
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
        <div class="card-body">
            <h3 class="fw-bold text-warning mb-4 mt-4 text-center">
                คำถามที่พบบ่อย (FAQ)
            </h3>
            <div class="accordion" id="faqAccordion">
                @php
                    $faqs = [
                        ['question' => 'ฉันสามารถจองห้องล่วงหน้าได้กี่วัน?', 'answer' => 'คุณสามารถจองห้องล่วงหน้าได้สูงสุด 30 วัน'],
                        ['question' => 'ฉันสามารถยกเลิกการจองได้หรือไม่?', 'answer' => 'คุณสามารถยกเลิกการจองได้สูงสุด 24 ชั่วโมงก่อนเวลาจอง'],
                        ['question' => 'ฉันจะชำระเงินได้อย่างไร?', 'answer' => 'คุณสามารถชำระเงินผ่านบัตรเครดิตหรือเงินสดที่สำนักงาน'],
                    ];
                @endphp

                @foreach ($faqs as $i => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading{{ $i }}">
                        <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $i }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $i }}">
                            {{ $faq['question'] }}
                        </button>
                    </h2>
                    <div id="faqCollapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" aria-labelledby="faqHeading{{ $i }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
