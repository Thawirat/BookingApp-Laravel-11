@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">ห้องทั้งหมด</h2>
                    <a href="{{ url('booking') }}" class="btn btn-outline-warning">
                        <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-4 pb-3">
                    @foreach ($rooms as $room)
                        @include('components.room-card', ['room' => $room])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
