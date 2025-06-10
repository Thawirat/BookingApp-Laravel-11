<div class="card me-3 flex-shrink-0" style="width: 300px;">
    <img src="{{ $room->image ? asset('storage/' . $room->image) : asset('images/no-picture.jpg') }}" class="card-img-top"
        alt="รูปภาพห้อง {{ $room->room_name }}" style="height: 200px; object-fit: cover;">
    <div class="card-body d-flex flex-column justify-content-between">
        <div class="ps-3 pe-3 pt-3 pb-3">
            <h5 class="fw-bold text-dark">{{ $room->room_name }}</h5>
            <p class="text-muted mb-1">
                <i class="fas fa-building me-1"></i> อาคาร {{ $room->building->building_name }}
                ชั้น {{ $room->class }}
            </p>
            <p class="text-muted mb-1">
                <i class="fas fa-users me-1"></i> {{ $room->capacity }} คน
            </p>
            <p class="text-muted mb-1">
                สถานะ
                <span class="badge bg-{{ $room->status->status_name === 'พร้อมใช้งาน' ? 'success' : 'danger' }}">
                    {{ $room->status->status_name }}
                </span>
            </p>
            @if ($room->status->status_name === 'พร้อมใช้งาน')
                <a href="{{ route('partials.booking.form', ['id' => $room->room_id]) }}"
                    class="btn btn-warning w-100">
                    จองห้องนี้
                </a>
            @else
                <button class="btn btn-secondary w-100" disabled>ไม่พร้อมให้จอง</button>
            @endif
        </div>
    </div>
</div>
