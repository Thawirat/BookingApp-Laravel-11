@extends('layouts.app')

@section('content')
    <h2>รีเซ็ตรหัสผ่าน</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label>อีเมล</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>รหัสผ่านใหม่</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>ยืนยันรหัสผ่าน</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">เปลี่ยนรหัสผ่าน</button>
    </form>
@endsection
