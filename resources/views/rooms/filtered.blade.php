@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">{{ $title }}</h2>
        <a href="{{ url('booking') }}" class="btn btn-outline-warning">
            <i class="fas fa-arrow-left me-1"></i> กลับหน้าหลัก
        </a>
    </div>

    @if($rooms->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($rooms as $room)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}"
                             class="card-img-top rounded-top"
                             style="height: 160px; object-fit: cover;"
                             alt="Room Image">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold text-dark">{{ $room->room_name }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-building me-1"></i> อาคาร {{ $room->building->building_name }} ชั้น {{ $room->class }}</p>
                                <p class="text-muted mb-1"><i class="fas fa-users me-1"></i> {{ $room->capacity }} คน</p>
                            </div>
                            <div>
                                <p class="fw-bold text-warning mt-2">฿{{ number_format($room->service_rates, 2) }} / ชั่วโมง</p>
                                <a href="{{ url('booking/'.$room->room_id) }}" class="btn btn-warning w-100 mt-2">จองเลย</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>ไม่พบห้องที่ตรงกับเงื่อนไข</h4>
            <p class="text-muted">กรุณาลองค้นหาใหม่อีกครั้ง</p>
        </div>
    @endif
</div>

@endsection
