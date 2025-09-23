<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รีเซ็ตรหัสผ่าน</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:#fdfdfd;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0"
        style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

        <!-- Header -->
        <tr>
            <td align="center"
                style="padding:30px; background: linear-gradient(to right, #ec4899, #60a5fa, #22c55e); color:white; border-top-left-radius:10px; border-top-right-radius:10px; text-align:center; box-shadow:0 4px 8px rgba(0,0,0,0.1);">
                <img src="{{ asset('images/snru.png') }}" alt="Logo"
                     style="max-width:90px; margin-bottom:12px;">
                <h2 style="margin:0; font-size:22px; font-weight:600; text-align:center;">รีเซ็ตรหัสผ่าน</h2>
                <p style="margin:5px 0 0; font-size:14px; text-align:center;">ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:30px; color:#333;">
                <h3 style="margin-top:0;">สวัสดี {{ $user->name }}</h3>
                <p style="font-size:15px; line-height:1.6;">
                    คุณได้ร้องขอการรีเซ็ตรหัสผ่านสำหรับบัญชี
                    <strong>{{ $user->email }}</strong>
                </p>

                <!-- OTP Box -->
                <div style="background:#f0f5ff; padding:20px; border-radius:8px; border:1px dashed #60a5fa; text-align:center; margin:25px 0;">
                    <p style="margin:0; font-size:16px; color:#333; text-align:center;">รหัส OTP ของคุณคือ:</p>
                    <p style="margin:15px 0 0; font-size:28px; font-weight:bold; color:#1d4ed8; letter-spacing:4px; text-align:center;">
                        {{ $otp }}
                    </p>
                    <p style="margin:10px 0 0; font-size:13px; color:#666; text-align:center;">รหัสนี้จะหมดอายุใน 10 นาที</p>
                </div>

                <p style="font-size:15px; line-height:1.6;">
                    หากคุณไม่ได้เป็นผู้ร้องขอการรีเซ็ตรหัสผ่านนี้
                    <strong style="color:#dc2626;">โปรดละเว้นอีเมลนี้ทันที</strong>
                </p>

                <p style="margin-top:30px; font-size:15px;">
                    ขอแสดงความนับถือ,<br>
                    <em>ระบบจองห้องประชุมออนไลน์<br>มหาวิทยาลัยราชภัฏสกลนคร</em>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td align="center" style="padding:15px; background:#f8f9fa; font-size:12px; color:#666;">
                © {{ date('Y') }} มหาวิทยาลัยราชภัฏสกลนคร
            </td>
        </tr>
    </table>
</body>
</html>
