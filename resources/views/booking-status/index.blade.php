@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">สถานะการจองห้องของฉัน</h2>

    @if($bookings->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ห้อง</th>
                    <th>อาคาร</th>
                    <th>วันที่จอง</th>
                    <th>วันเวลาจองเริ่มต้น</th>
                    <th>วันเวลาจองสิ้นสุด</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->room_name ?? '-' }}</td>
                        <td>{{ $booking->building_name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->crated_add)->format('d/m/Y')}}</td>
                        <td>{{ $booking->booking_start }}</td>
                        <td>{{ $booking->booking_end }}</td>
                        <td>{{ $booking->status->status_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $bookings->links() }}
    @else
        <p>ยังไม่มีการจอง</p>
    @endif
</div>
@endsection
