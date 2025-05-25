<div class="d-flex flex-column align-items-center gap-2">
    <span
        class="badge
@if ($booking->payment_status == 'paid') bg-success
@elseif($booking->payment_status == 'pending') bg-info text-dark
@elseif($booking->payment_status == 'cancelled') bg-danger
@else bg-warning text-dark @endif"
        data-bs-toggle="tooltip" data-bs-placement="top" title="สถานะการชำระเงิน">
        {{ match ($booking->payment_status) {
            'unpaid' => 'ยังไม่ชำระ',
            'paid' => 'ชำระเงินแล้ว',
            'partial' => 'ชำระบางส่วน',
            'pending' => 'รอตรวจสอบ',
            'cancelled' => 'ยกเลิกการชำระ',
        } }}
    </span>

    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-wallet"></i> เปลี่ยนสถานะ
        </button>
        <ul class="dropdown-menu">
            <li>
                <button class="dropdown-item" data-bs-toggle="modal"
                    data-bs-target="#paymentSlipModal{{ $booking->id }}">
                    <i class="fas fa-file-invoice me-2"></i> ดูสลิปการชำระ
                </button>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_status" value="paid">
                    <button type="submit" class="dropdown-item text-success">
                        <i class="fas fa-check-circle me-2"></i> ชำระครบถ้วน
                    </button>
                </form>
            </li>
            <li>
                <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_status" value="partial">
                    <button type="submit" class="dropdown-item text-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ชำระบางส่วน
                    </button>
                </form>
            </li>
            <li>
                <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_status" value="cancelled">
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        ยกเลิกการชำระ
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
