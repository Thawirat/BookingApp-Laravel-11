<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติการจองห้องของฉัน</title>
    <style>
        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: "THSarabun", sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>ประวัติการจองห้องของฉัน</h2>

    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสการจอง</th>
                <th>ชื่อห้อง</th>
                <th>อาคาร</th>
                <th>วันที่จอง</th>
                <th>เริ่มต้น</th>
                <th>สิ้นสุด</th>
                <th>สถานะ</th>
                <th>การชำระเงิน</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->room_name ?? '-' }}</td>
                    <td>{{ $booking->building_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y H:i') }}</td>
                    <td>{{ $booking->status->status_name ?? '-' }}</td>
                    <td>
                        @php
                            $statusText =
                                [
                                    'paid' => 'ชำระแล้ว',
                                    'pending' => 'รอตรวจสอบ',
                                    'unpaid' => 'ยังไม่ชำระ',
                                    'cancelled' => 'ยกเลิก',
                                ][$booking->payment_status] ?? 'ไม่ทราบ';
                        @endphp
                        {{ $statusText }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
