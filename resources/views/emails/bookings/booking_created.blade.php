<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แจ้งเตือนการจองห้องใหม่</title>
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
                <h2 style="margin:0; font-size:22px; font-weight:600; text-align:center;">
                    มีการจองห้องใหม่เข้ามาในระบบ
                </h2>
                <p style="margin:5px 0 0; font-size:14px; text-align: center;">
                    ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:30px; color:#333;">
                <h3 style="margin-top:0; color:#333;">📢 แจ้งเตือนผู้ดูแล</h3>
                <p style="font-size:16px; color:#333;">
                    มีการจองห้องใหม่เข้ามาในระบบ กรุณาตรวจสอบและดำเนินการอนุมัติ/ปฏิเสธตามความเหมาะสม
                </p>

                <div
                    style="background:#f0f5ff; padding:20px; border-radius:8px; border:1px solid #cbd5e1; margin:20px 0; font-size:14px;">
                    <strong>รายละเอียดการจอง:</strong>
                    <ul style="padding-left:20px; margin-top:10px; margin-bottom:0;">
                        <li><strong>ผู้จอง:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</li>
                        <li><strong>ห้อง:</strong> {{ $booking->room->room_name }}</li>
                        <li><strong>รหัสการจอง:</strong> {{ $booking->booking_id }}</li>
                        <li><strong>เรื่อง:</strong> {{ $booking->title }}</li>
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
                    </ul>
                </div>

                <p style="margin-top:15px;">
                    สามารถเข้าไปดูรายละเอียดและอนุมัติการจองได้ที่ระบบผู้ดูแล
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
