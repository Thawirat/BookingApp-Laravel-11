@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-comment-dots me-2"></i> ข้อเสนอแนะจากผู้ใช้</h2>
        @if ($feedbacks->count())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">ลำดับที่</th>
                            {{-- <th>ผู้ใช้</th> --}}
                            <th class="text-nowrap text-center">ข้อความ</th>
                            <th class="text-nowrap">วันที่ส่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $index => $feedback)
                            <tr>
                                <td class="text-center">{{ $feedbacks->firstItem() + $index }}</td>
                                {{-- <td>{{ $feedback->user->name ?? '-' }}</td> --}}
                                <td class="wrap-text">{{ $feedback->message }}</td>
                                <td class="text-center">{{ $feedback->created_at->addYears(543)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $feedbacks->links('pagination::bootstrap-5') }}
        @else
            <div class="alert alert-info">ยังไม่มีข้อเสนอแนะจากผู้ใช้</div>
        @endif
    </div>
    <style>
        .wrap-text {
            word-break: break-word;
            white-space: pre-wrap;
        }
    </style>

@endsection
