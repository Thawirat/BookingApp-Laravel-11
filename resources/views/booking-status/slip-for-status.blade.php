<div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">จัดการหลักฐานการชำระเงิน
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($booking->payment_slip)
                    {{-- แสดงสลิปที่อัปโหลด --}}
                    <p><strong>หลักฐานที่อัปโหลด:</strong></p>
                    @if (Str::endsWith($booking->payment_slip, ['.jpg', '.jpeg', '.png']))
                        <img src="{{ asset('storage/' . $booking->payment_slip) }}" alt="สลิปชำระเงิน"
                            class="img-fluid rounded border mb-3">
                    @else
                        <a href="{{ asset('storage/' . $booking->payment_slip) }}" target="_blank"
                            class="btn btn-outline-secondary mb-3">
                            <i class="bi bi-file-earmark-pdf"></i> เปิดไฟล์แนบ
                        </a>
                    @endif
                    {{-- ปุ่มดู QR Code --}}
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#qrCodeCollapse{{ $booking->id }}">
                            <i class="bi bi-qr-code"></i> ดู QR Code สำหรับการชำระเงิน
                        </button>
                    </div>

                    <div class="collapse mt-3" id="qrCodeCollapse{{ $booking->id }}">
                        <img src="{{ asset('images/apple-icon.png') }}" alt="QR Code ธนาคาร"
                            class="img-fluid rounded-lg shadow-sm mb-3" style="max-width: 160px;">

                        <!-- Bank Details -->
                        <div class="text-start bg-white p-3 rounded-3 mb-3 text-center">
                            <p class="mb-1"><span class="text-muted">ชื่อบัญชี:</span>
                                <span class="fw-semibold">บริษัท ABC จำกัด</span>
                            </p>
                            <p class="mb-1"><span class="text-muted">ธนาคาร:</span>
                                <span class="fw-semibold">ไทยพาณิชย์</span>
                            </p>
                            <p class="mb-0"><span class="text-muted">เลขบัญชี:</span>
                                <span class="fw-semibold">123-456-7890</span>
                            </p>
                        </div>
                    </div>
                @else
                    {{-- ยังไม่มีการอัปโหลด – แสดง QR เลย --}}
                    <div class="alert alert-warning">
                        ยังไม่มีการอัปโหลดหลักฐานการชำระเงิน</div>
                    <p><strong>สแกนเพื่อชำระเงิน:</strong></p>
                    <img src="{{ asset('images/apple-icon.png') }}" alt="QR Code ธนาคาร"
                        class="img-fluid rounded-lg shadow-sm mb-3" style="max-width: 160px;">

                    <!-- Bank Details -->
                    <div class="text-start bg-white p-3 rounded-3 mb-3">
                        <p class="mb-1"><span class="text-muted">ชื่อบัญชี:</span>
                            <span class="fw-semibold">บริษัท ABC จำกัด</span>
                        </p>
                        <p class="mb-1"><span class="text-muted">ธนาคาร:</span> <span
                                class="fw-semibold">ไทยพาณิชย์</span></p>
                        <p class="mb-0"><span class="text-muted">เลขบัญชี:</span>
                            <span class="fw-semibold">123-456-7890</span>
                        </p>
                    </div>
                @endif
                <hr>
                {{-- ฟอร์มอัปโหลดหลักฐาน --}}
                <form action="{{ route('booking.uploadSlip', $booking->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="paymentSlip{{ $booking->id }}" class="form-label">อัปโหลดสลิปชำระเงิน</label>
                        <input type="file" name="payment_slip" class="form-control"
                            id="paymentSlip{{ $booking->id }}" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up me-1"></i> อัปโหลดสลิป
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
