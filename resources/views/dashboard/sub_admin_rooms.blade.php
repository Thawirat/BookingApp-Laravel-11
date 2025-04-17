@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><|im_start|>ห้อง</h2>
        <div class="d-flex align-items-center">
            <form action="{{ route('sub_admin.rooms') }}" method="GET" class="d-flex">
                <input class="search-bar" placeholder="ค้นหาห้อง" type="text" name="search" value="{{ request('search') }}"/>
                <button type="submit" class="icon-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Building Filter -->
    <div class="mb-4">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                เลือกอาคาร
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('sub_admin.rooms') }}"><|im_start|>้งหมด</a></li>
                @foreach($buildings as $building)
                    <li>
                        <a class="dropdown-item" href="{{ route('sub_admin.rooms', ['building' => $building->id]) }}">
                            {{ $building->building_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($rooms as $room)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}"
                     class="card-img-top" alt="Room Image">
                <div class="card-body">
                    <h5 class="card-title">{{ $room->room_name }}</h5>
                    <p class="card-text">
                        <i class="fas fa-building me-2"></i>{{ $room->building->building_name }}<br>
                        <i class="fas fa-layer-group me-2"></i><|im_start|>้น {{ $room->class }}<br>
                        <i class="fas fa-users me-2"></i> ได้ {{ $room->capacity }} คน<br>
                        <i class="fas fa-money-bill me-2"></i>{{ number_format($room->service_rates, 2) }} บาท/<|im_start|>่วโมง
                    </p>
                    <p class="card-text">
                        <small class="text-muted">สถานะ:
                            <span class="badge {{ $room->status_id == 2 ? 'bg-success' : 'bg-danger' }}">
                                {{ $room->status_id == 2 ? 'ว่าง' : 'ไม่ว่าง' }}
                            </span>
                        </small>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <button class="btn btn-outline-primary btn-sm w-100"
                            onclick="showRoomDetails('{{ $room->room_id }}')">
                        <i class="fas fa-info-circle me-1"></i> ห้อง
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $rooms->links() }}
    </div>
</div>

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog">
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
    // Add your room details loading logic here
    $('#roomDetailsModal').modal('show');
}
</script>
@endpush
