@extends('layouts.app') {{-- หรือเปลี่ยนตาม layout ที่ใช้จริง --}}

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow p-4 w-100" style="max-width: 400px;">
        <h4 class="mb-4 text-center">ยืนยันรหัส OTP</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.otp') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="otp" class="form-label">กรอกรหัส OTP</label>
                <input type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" id="otp" required autofocus>

                @error('otp')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- อาจจะต้องมี hidden email ถ้าไม่ได้เก็บใน session --}}
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">

            <button type="submit" class="btn btn-primary w-100">ยืนยันรหัส</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('password.request') }}">ส่งรหัสใหม่</a>
        </div>
    </div>
</div>
@endsection
