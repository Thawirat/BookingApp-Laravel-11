<div class="card rounded-lg border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h4 class="mb-0 fw-bold">ข้อมูลห้องพัก</h4>
    </div>
    <div class="card-body p-4">
        <!-- Room Image -->
        <div class="mb-4">
            @if (isset($room->image))
                <img src="{{ asset('storage/' .$room->image) }}" alt="{{ $room->room_name }}" class="img-fluid rounded-lg">
            @else
                <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5">
                    <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                </div>
            @endif
        </div>
        <!-- Room Details -->
        <div class="mb-3">
            <!-- Building -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">อาคาร:</span>
                <span>{{ $room->building->building_name ?? 'ไม่ระบุ' }}</span>
            </div>
            <!-- Room Name -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ชื่อห้อง:</span>
                <span class="fw-bold">{{ $room->room_name }}</span>
            </div>
            <!-- Floor -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ชั้น:</span>
                <span>{{ $room->class }}</span>
            </div>
            <!-- Capacity -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ความจุ:</span>
                <span>{{ $room->capacity ?? '-' }} คน</span>
            </div>
            <!-- Details -->
            <div class="py-2">
                <div class="text-muted mb-1">รายละเอียด:</div>
                <p class="mb-0">{{ $room->room_details }}</p>
            </div>
        </div>
    </div>
</div>
