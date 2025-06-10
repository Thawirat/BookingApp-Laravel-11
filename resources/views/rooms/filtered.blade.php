@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark mb-0">{{ $title }}</h2>
            <a href="{{ url('booking') }}" class="btn btn-outline-warning">
                <i class="fas fa-arrow-left me-1"></i> กลับหน้าหลัก
            </a>
        </div>

        @if ($rooms->count() > 0)
            <div class="grid grid-cols-4 gap-4 pb-3">
                @foreach ($rooms as $room)
                    @include('components.room-card', ['room' => $room])
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
