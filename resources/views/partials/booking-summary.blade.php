<div class="card shadow rounded-lg border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h4 class="mb-0 fw-bold">สรุปการจอง</h4>
    </div>
    <div class="card-body p-4">
        <!-- Price Summary -->
        <div class="mb-4">
            <!-- Rate -->
            {{-- <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">อัตราค่าบริการ:</span>
                <span class="fw-bold">{{ number_format($room->service_rates ?? 0, 2) }} บาท/ชั่วโมง</span>
            </div> --}}
            <!-- Days -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">จำนวนวัน:</span>
                <span id="totalDays">0 วัน</span>
            </div>
            <!-- Check-in time -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">เวลาเข้า:</span>
                <span id="checkInTime"></span>
            </div>
            <!-- Check-out time -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">เวลาออก:</span>
                <span id="checkOutTime"></span>
            </div>
            <!-- Service Fee -->
            {{-- <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ค่าบริการทั้งหมด:</span>
                <span id="serviceFee">0 บาท</span>
            </div> --}}
            <!-- Total Price -->
            {{-- <div class="d-flex justify-content-between py-3">
                <span class="fw-bold">ราคารวมทั้งสิ้น:</span>
                <span class="fw-bold text-warning h5 mb-0" id="totalPrice">0 บาท</span>
            </div> --}}
        </div>
        <!-- Bank Payment -->
        {{-- <div class="mb-3">
            <div class="form-check mb-3">
                <input type="checkbox" id="bankPaymentCheckbox" class="form-check-input">
                <label for="bankPaymentCheckbox" class="form-check-label fw-semibold">
                    <i class="bi bi-bank me-1"></i>ชำระผ่านธนาคาร
                </label>
            </div>
            <!-- Bank Payment Details -->
            <div id="bankPaymentDetails" class="d-none p-3 bg-light rounded-3 text-center">
                <h5 class="fw-bold mb-3">โอนเงินผ่านธนาคาร</h5>
                <!-- QR Code -->
                <img src="{{ asset('images/apple-icon.png') }}" alt="QR Code ธนาคาร"
                    class="img-fluid rounded-lg shadow-sm mb-3" style="max-width: 160px;">
                <!-- Bank Details -->
                <div class="text-start bg-white p-3 rounded-3 mb-3">
                    <p class="mb-1"><span class="text-muted">ชื่อบัญชี:</span> <span class="fw-semibold">บริษัท ABC
                            จำกัด</span></p>
                    <p class="mb-1"><span class="text-muted">ธนาคาร:</span> <span
                            class="fw-semibold">ไทยพาณิชย์</span></p>
                    <p class="mb-0"><span class="text-muted">เลขบัญชี:</span> <span
                            class="fw-semibold">123-456-7890</span></p>
                </div>
                <!-- Upload Slip Button -->
                <div class="mt-3">
                    <label for="paymentSlip"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-upload me-2"></i>อัปโหลดสลิป
                    </label>
                    <input type="file" id="paymentSlip" name="payment_slip" class="d-none"
                        accept="image/*,application/pdf" form="bookingForm">
                    <div id="fileName" class="small text-muted mt-2">ยังไม่ได้เลือกไฟล์</div>
                </div>
            </div>
        </div> --}}
        <!-- Note -->
        {{-- <div class="bg-light p-3 rounded-3 small">
            <p class="mb-0 text-primary">
                <i class="bi bi-info-circle-fill me-1"></i>
                <span class="fw-semibold">หมายเหตุ:</span>
                ราคาอาจมีการเปลี่ยนแปลงตามนโยบายและระยะเวลาที่จอง
            </p>
        </div> --}}
    </div>
</div>
