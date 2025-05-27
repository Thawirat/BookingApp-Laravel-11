<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดการจองห้อง</title>
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
        }
        .section {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">รายละเอียดการจองห้อง</h2>

    <div class="section">
        <div><span class="label">รหัสการจอง:</span> {{ $booking->id }}</div>
        <div><span class="label">ชื่อห้อง:</span> {{ $booking->room_name ?? '-' }}</div>
        <div><span class="label">อาคาร:</span> {{ $booking->building_name ?? '-' }}</div>
        <div><span class="label">วันที่จอง:</span> {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}</div>
    </div>

    <div class="section">
        <div><span class="label">เริ่มต้น:</span> {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y H:i') }}</div>
        <div><span class="label">สิ้นสุด:</span> {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="section">
        <div><span class="label">สถานะ:</span> {{ $booking->status->status_name ?? '-' }}</div>
        <div><span class="label">การชำระเงิน:</span>
            @php
                $statusText = [
                    'paid' => 'ชำระแล้ว',
                    'pending' => 'รอตรวจสอบ',
                    'unpaid' => 'ยังไม่ชำระ',
                    'cancelled' => 'ยกเลิก',
                ][$booking->payment_status] ?? 'ไม่ทราบ';
            @endphp
            {{ $statusText }}
        </div>
    </div>

</body>
</html>
