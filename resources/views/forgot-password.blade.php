@extends('layouts.app')

@section('content')
    <h1>ลืมรหัสผ่าน</h1>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email">อีเมล</label>
            <input type="email" name="email" required class="form-control" />
        </div>
        <button type="submit" class="btn btn-primary">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
    </form>
@endsection
