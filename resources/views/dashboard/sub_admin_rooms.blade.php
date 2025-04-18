@extends('layouts.app')

@section('content')
<div class="container mt-5 card mb-4 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการห้องใน {{ request('building') ? $buildings->where('id', request('building'))->first()->building_name : '' }}</h2>
            <div class="d-flex align-items-center gap-3">
                <!-- Search Form -->
                <form action="{{ route('sub_admin.rooms') }}" method="GET" class="d-flex">
                    @if(request('building'))
                        <input type="hidden" name="building" value="{{ request('building') }}">
                    @endif
                    <div class="input-group">
                        <input class="form-control" placeholder="ค้นหาห้อง" type="text" name="search" value="{{ request('search') }}"/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($rooms as $room)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : asset('images/no-picture.jpg') }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;"
                             alt="{{ $room->room_name }}">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-{{ $room->status_id == 2 ? 'success' : 'danger' }}">
                                {{ $room->status->status_name }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $room->room_name }}</h5>
                        <div class="card-text small">
                            <div class="mb-1"><i class="fas fa-building me-2"></i>{{ $room->building->building_name }}</div>
                            <div class="mb-1"><i class="fas fa-layer-group me-2"></i>ชั้น {{ $room->class }}</div>
                            <div class="mb-1"><i class="fas fa-users me-2"></i>ความจุ {{ $room->capacity }} คน</div>
                            <div class="mb-1"><i class="fas fa-money-bill me-2"></i>{{ number_format($room->service_rates, 2) }} บาท/ชั่วโมง</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 p-3">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm flex-grow-1"
                                    onclick="showRoomDetails('{{ $room->room_id }}')">
                                <i class="fas fa-info-circle me-1"></i> ดูรายละเอียด
                            </button>
                            <button class="btn btn-warning btn-sm flex-grow-1"
                                    onclick="openEditRoomModal(
                                        '{{ $room->room_id }}',
                                        '{{ $room->room_name }}',
                                        '{{ $room->capacity }}',
                                        '{{ $room->class }}',
                                        '{{ $room->room_details }}',
                                        '{{ $room->service_rates }}',
                                        '{{ $room->image ? asset('storage/' . $room->image) : '' }}'
                                    )">
                                <i class="fas fa-edit me-1"></i> แก้ไข
                            </button>
                            <button class="btn btn-danger btn-sm flex-grow-1"
                                    onclick="confirmDeleteRoom('{{ $room->room_id }}')">
                                <i class="fas fa-trash me-1"></i> ลบ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p class="h5">ไม่พบห้องค้นหา</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->appends(['search' => request('search'), 'building' => request('building')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">น่มห้องใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('manage_rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="building_id" value="{{ request('building') }}">
                <div class="modal-body">
                    <!-- Add room form fields here, matching rooms.blade.php -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">แก้ไขห้อง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Edit room form fields here, matching rooms.blade.php -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ยืนยันการลบห้อง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจว่าต้องการลบห้องนี้?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteRoomForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger">ลบ</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showRoomDetails(roomId) {
    const modal = $('#roomDetailsModal');
    const content = $('#roomDetailsContent');

    content.html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
    modal.modal('show');

    $.get(`/admin/rooms/${roomId}/details`, function(data) {
        content.html(data);
    }).fail(function() {
        content.html('<div class="alert alert-danger">ไม่สามารถโหลดข้อมูลได้</div>');
    });
}

function openEditRoomModal(roomId, roomName, capacity, roomClass, roomDetails, serviceRates, imageUrl) {
    document.getElementById('editRoomForm').action = `/manage_rooms/${roomId}`;

    document.getElementById('edit_room_name').value = roomName;
    document.getElementById('edit_capacity').value = capacity;
    document.getElementById('edit_class').value = roomClass;
    document.getElementById('edit_room_details').value = roomDetails;
    document.getElementById('edit_service_rates').value = serviceRates;

    const currentImageDiv = document.getElementById('currentImage');
    if (imageUrl) {
        currentImageDiv.innerHTML = `<img src="${imageUrl}" alt="Current Image" style="max-width: 100%; height: auto;"/>`;
    } else {
        currentImageDiv.innerHTML = '<p>ไม่มีรูปภาพ</p>';
    }

    $('#editRoomModal').modal('show');
}

function confirmDeleteRoom(roomId) {
    document.getElementById('deleteRoomForm').action = `/manage_rooms/${roomId}`;
    $('#deleteRoomModal').modal('show');
}
</script>
@endpush

