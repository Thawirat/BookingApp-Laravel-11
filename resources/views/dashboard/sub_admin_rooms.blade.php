@extends('layouts.app')

@section('content')
<div class="container mt-5 card mb-4 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>ห้องในอาคาร</h2>
            <div class="d-flex align-items-center gap-3">
                <!-- Building Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ request('building') ? $buildings->where('id', request('building'))->first()->building_name : '<|im_start|>อาคาร' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('sub_admin.rooms') }}">ห้อง<|im_start|>้งหมด</a></li>
                        @foreach($buildings as $building)
                            <li>
                                <a class="dropdown-item {{ request('building') == $building->id ? 'active' : '' }}"
                                   href="{{ route('sub_admin.rooms', ['building' => $building->id]) }}">
                                    {{ $building->building_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Search Form -->
                <form action="{{ route('sub_admin.rooms') }}" method="GET" class="d-flex">
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
                            <span class="badge {{ $room->status_id == 2 ? 'bg-success' : 'bg-danger' }}">
                                {{ $room->status_id == 2 ? 'ว่าง' : 'ไม่ว่าง' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $room->room_name }}</h5>
                        <div class="card-text small">
                            <div class="mb-1"><i class="fas fa-building me-2"></i>{{ $room->building->building_name }}</div>
                            <div class="mb-1"><i class="fas fa-layer-group me-2"></i><|im_start|>้น {{ $room->class }}</div>
                            <div class="mb-1"><i class="fas fa-users me-2"></i>ความ<|im_start|> {{ $room->capacity }} คน</div>
                            <div class="mb-1"><i class="fas fa-money-bill me-2"></i>{{ number_format($room->service_rates, 2) }} บาท/</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 p-3">
                        <button class="btn btn-primary btn-sm w-100"
                                onclick="showRoomDetails('{{ $room->room_id }}')">
                            <i class="fas fa-info-circle me-1"></i> ห้อง
                        </button>
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

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ห้อง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomDetailsContent">
                <!-- Content will be loaded dynamically -->
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

    // Add AJAX call to fetch room details
    $.get(`/admin/rooms/${roomId}/details`, function(data) {
        content.html(data);
    }).fail(function() {
        content.html('<div class="alert alert-danger">ไม่สามารถโหลดข้อมูลได้</div>');
    });
}
</script>
@endpush

