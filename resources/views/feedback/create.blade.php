@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-comment-dots me-2"></i> ส่งความคิดเห็น</h2>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('feedback.store') }}">
            @csrf
            <div class="mb-3">
                <label for="message" class="form-label">ความคิดเห็นของคุณ</label>
                <textarea name="message" id="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                @error('message')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> ส่งความคิดเห็น</button>
        </form>
    </div>
@endsection
