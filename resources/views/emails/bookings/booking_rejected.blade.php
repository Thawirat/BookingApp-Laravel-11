<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>การแจ้งผลการจองห้อง</title>
</head>

<body
    style="margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:#fdfdfd;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0"
        style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <!-- Header -->
        <tr>
            <td align="center"
                style="padding:30px; background: linear-gradient(to right, #ec4899, #60a5fa, #22c55e); color:white; border-top-left-radius:10px; border-top-right-radius:10px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.1);">
                <img src="{{ asset('images/snru.png') }}" alt="Logo"
                    style="max-width:100px; margin-bottom:12px; border-radius:50%;">
                <h2 style="margin:0; font-size:24px; font-weight:600;">ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฎสกลนคร
                </h2>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:30px; color:#333;">
                <h3 style="margin-top:0; color:#333;">เรียนคุณ {{ $booking->user->name }}</h3>
                <p style="font-size:16px; color:#333;">
                    ทางระบบขอแจ้งให้ท่านทราบว่า
                    การจองห้องของท่าน <strong style="color:#f43f5e;">ไม่ได้รับการอนุมัติ</strong> ❌
                    กรุณาติดต่อเจ้าหน้าที่หากต้องการสอบถามรายละเอียดเพิ่มเติม
                </p>

                <div
                    style="background:#f0f5ff; padding:20px; border-radius:8px; border:1px solid #cbd5e1; margin:20px 0; font-size:14px;">
                    <strong>รายละเอียดการจอง: {{ $booking->room->room_name }}</strong>
                    <ul style="padding-left:20px; margin-top:10px; margin-bottom:0;">
                        <li><strong>รหัสการจอง:</strong> {{ $booking->booking_id }}</li>
                        <li><strong>เรื่อง:</strong> {{ $booking->title }}</li>
                        <li><strong>สถานะการจอง:</strong> {{ $booking->status->status_name }}</li>
                        <li><strong>วันที่จอง:</strong>
                            {{ \Carbon\Carbon::parse($booking->booking_created_at)->addYear(543)->format('d/m/Y') }}
                        </li>
                        <li><strong>วันที่จัด-เก็บสถานที่:</strong>
                            {{ \Carbon\Carbon::parse($booking->setup_date)->addYear(543)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($booking->teardown_date)->addYear(543)->format('d/m/Y') }}</li>
                        <li><strong>วันที่เริ่มต้น-สิ้นสุด:</strong>
                            {{ \Carbon\Carbon::parse($booking->booking_start)->addYear(543)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($booking->booking_end)->addYear(543)->format('d/m/Y') }}</li>
                        <li><strong>เวลา:</strong> {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }}
                            น. - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }} น.</li>
                        <li><strong>วัตถุประสงค์:</strong> {{ $booking->reason ?? 'ไม่ระบุ' }}</li>
                        <li><strong>จำนวนผู้เข้าร่วม:</strong> {{ $booking->participant_count ?? 'ไม่ระบุ' }} คน</li>
                        <li><strong>รายละเอียดกิจกรรม:</strong> {{ $booking->booker_info ?? 'ไม่ระบุ' }}</li>
                        <li><strong>อุปกรณ์ในห้อง:</strong>
                            @if ($booking->room && $booking->room->equipments && $booking->room->equipments->count())
                                <ul style="padding-left:15px; margin:5px 0;">
                                    @foreach ($booking->room->equipments as $equipment)
                                        <li>{{ $equipment->name }} {{ $equipment->quantity }} รายการ</li>
                                    @endforeach
                                </ul>
                            @else
                                ไม่มีอุปกรณ์
                            @endif
                        </li>
                    </ul>
                </div>

                <p>
                    หากท่านมีข้อสงสัยเพิ่มเติม สามารถติดต่อเจ้าหน้าที่ผู้ดูแลระบบได้ทุกเวลา
                </p>

                <p style="margin-top:30px;">
                    ขอแสดงความนับถือ<br>
                    <em>ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฎสกลนคร</em>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td align="center" style="padding:15px; background:#f8f9fa; font-size:12px; color:#666;">
                © {{ date('Y') }} มหาวิทยาลัยราชภัฎสกลนคร
            </td>
        </tr>
    </table>
</body>

</html>
