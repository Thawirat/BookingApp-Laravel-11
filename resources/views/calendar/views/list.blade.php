<div class="calendar-list">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th width="100">วันที่</th>
                    <th width="120">เวลา</th>
                    <th>ห้อง</th>
                    <th>อาคาร</th>
                    <th>ผู้จอง</th>
                    <th width="120">สถานะ</th>
                    <th>เหตุผล</th>
                    <th width="80"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listBookings as $booking)
                    @if (!in_array($booking->status_id, [1, 2]))
                        {{-- ซ่อนสถานะ 1 และ 2 --}}
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_start)->locale('th')->translatedFormat('d/m/') . (\Carbon\Carbon::parse($booking->booking_start)->year + 543) }}
                            </td>
                            <td>
                                {{ Carbon\Carbon::parse($booking->booking_start)->format('H:i') }}<br>
                                {{ Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                            </td>
                            <td>{{ $booking->room_name }}</td>
                            <td>{{ $booking->building_name }}</td>
                            <td>{{ $booking->external_name }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $booking->statusColor }}">
                                    {{ $booking->status_name }}
                                </span>
                            </td>
                            <td>{{ Str::limit($booking->reason, 30) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal{{ $booking->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                            @include('booking-status.modal')
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($listBookings->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="far fa-calendar-alt fa-3x mb-3"></i>
            <p>ไม่พบข้อมูลการจองในเดือนนี้</p>
        </div>
    @endif
</div>
