<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แจ้งเตือนเปลี่ยนรหัสผ่าน</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color:#fdfdfd;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0"
        style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

        <!-- Header -->
        <tr>
            <td align="center"
                style="padding:30px; background:linear-gradient(to right, #ec4899, #60a5fa, #22c55e); color:white; border-top-left-radius:10px; border-top-right-radius:10px; text-align:center;">
                <img src="{{ asset('images/snru.png') }}" alt="Logo"
                     style="max-width:90px; margin-bottom:12px; border-radius:50%;">
                <h2 style="margin:0; font-size:22px; font-weight:600;">เปลี่ยนรหัสผ่านสำเร็จ</h2>
                <p style="margin:5px 0 0; font-size:14px;">ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:30px; color:#333; font-size:15px; line-height:1.7;">
                <p>สวัสดี <strong>{{ $user->name }}</strong>,</p>

                <p>
                    ทางระบบขอแจ้งให้ทราบว่า บัญชีของคุณ
                    (<strong>{{ $user->email }}</strong>)
                    ได้มีการ <span style="color:#22c55e; font-weight:600;">รีเซ็ตรหัสผ่าน</span> เมื่อไม่นานมานี้
                </p>

                <div style="background:#fef2f2; padding:15px; border-left:4px solid #ef4444; margin:20px 0; border-radius:6px; color:#b91c1c;">
                    ⚠️ หากคุณไม่ได้เป็นผู้ดำเนินการ กรุณาติดต่อผู้ดูแลระบบทันที
                </div>

                <p style="margin-top:20px;">
                    ขอแสดงความนับถือ,<br>
                    <em>ระบบจองห้องประชุมออนไลน์ มหาวิทยาลัยราชภัฏสกลนคร</em>
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
