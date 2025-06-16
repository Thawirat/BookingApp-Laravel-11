<div class="d-flex flex-column align-items-center gap-2">
    {{-- ป้ายสถานะ --}}
    <span
        class="badge
                                                                @if ($booking->status_id == 3) bg-warning
                                                                @elseif($booking->status_id == 4) bg-success
                                                                @elseif($booking->status_id == 5) bg-danger
                                                                @else bg-secondary @endif"
        data-bs-toggle="tooltip" data-bs-placement="top"
        title="อนุมัติโดย: {{ $booking->approver_name ?? 'ยังไม่มีผู้อนุมัติ' }}">
        {{ $booking->status_name }}
    </span>
    {{-- ปุ่มเปลี่ยนสถานะ --}}
    <div class="dropdown">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-edit"></i> เปลี่ยนสถานะ
        </button>
        <ul class="dropdown-menu">
            @foreach (\App\Enums\BookingStatus::options() as $status => $info)
                <li>
                    <form action="{{ route('booking.update-status', $booking->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_id" value="{{ $status }}">
                        <button type="submit" class="dropdown-item {{ $info['class'] }}">
                            <i class="{{ $info['icon'] }}"></i>
                            {{ $info['label'] }}
                        </button>
                    </form>
                </li>
                @if ($loop->iteration === 2)
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
