<div class="modal fade" id="paymentSlipModal{{ $booking->id }}" tabindex="-1"
    aria-labelledby="paymentSlipModalLabel{{ $booking->id }}" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h2 class="modal-title h5" id="paymentSlipModalLabel{{ $booking->id }}">
                    <i class="fas fa-file-invoice me-2"></i>สลิปการชำระเงิน
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="ปิดหน้าต่างสลิปการชำระเงิน"></button>
            </div>
            <div class="modal-body text-center">
                <div class="payment-slip-container">
                    @if ($booking->payment_slip && Storage::disk('public')->exists('payment_slips/' . basename($booking->payment_slip)))
                        <div style="max-width: 100%; overflow: auto; margin: 0 auto;">
                            <img src="{{ Storage::url($booking->payment_slip) }}"
                                alt="สลิปการชำระเงินสำหรับการจองหมายเลข {{ $booking->id }}"
                                class="img-fluid rounded shadow-sm mb-3"
                                style="max-height: 60vh; width: auto; display: block; margin: 0 auto;">
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบสลิปการชำระเงิน
                        </div>
                    @endif

                    <div class="payment-details bg-light p-3 rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>เลขที่การจอง:</strong>
                                <span class="badge bg-secondary">{{ $booking->id }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>วันที่ชำระ:</strong>
                                {{ \Carbon\Carbon::parse($booking->updated_at)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>ยอดชำระ:</strong>
                                {{ number_format($booking->total_price, 2) }}
                                บาท
                            </div>
                            <div class="col-md-6">
                                <strong>สถานะ:</strong>
                                <span
                                    class="badge
                                                                    @if ($booking->payment_status == 'paid') bg-success
                                                                    @elseif($booking->payment_status == 'partial') bg-warning
                                                                    @elseif($booking->payment_status == 'cancelled') bg-danger
                                                                    @else bg-secondary @endif">
                                    {{ match ($booking->payment_status) {
                                        'paid' => 'ชำระครบถ้วน',
                                        'partial' => 'ชำระบางส่วน',
                                        'cancelled' => 'ยกเลิกการชำระ',
                                        default => 'รอการชำระ',
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>ปิด
                </button>
                @if ($booking->payment_slip && Storage::disk('public')->exists('payment_slips/' . basename($booking->payment_slip)))
                    <a href="{{ Storage::url($booking->payment_slip) }}" target="_blank" class="btn btn-primary"
                        download="สลิปการชำระเงิน_{{ $booking->id }}.jpg">
                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
