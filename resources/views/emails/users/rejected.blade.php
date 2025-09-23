<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บัญชียังไม่ได้รับการอนุมัติ</title>
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
                <h2 style="margin:0; font-size:22px; font-weight:600; text-align: center;">บัญชียังไม่ได้รับการอนุมัติ</h2>
                <p style="margin:5px 0 0; font-size:14px; text-align: center;">ระบบจองห้องประชุมออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:30px; color:#333;">
                <h3 style="margin-top:0; color:#e11d48;">❌ บัญชียังไม่ได้รับการอนุมัติ</h3>
                <p style="font-size:16px; line-height:1.6;">
                    สวัสดี <strong>{{ $user->name }}</strong> (<em>{{ $user->email }}</em>)<br><br>
                    ทางระบบขอแจ้งให้ท่านทราบว่า <strong style="color:#e11d48;">บัญชีของคุณยังไม่ได้รับการอนุมัติ</strong>
                    ให้ใช้งานระบบในขณะนี้
                </p>

                <div style="background:#fff0f3; padding:20px; border-radius:8px; border:1px solid #fda4af; margin:20px 0; font-size:14px;">
                    ℹ️ หากคุณต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่หรือผู้ดูแลระบบ
                </div>

                <p style="margin-top:20px; font-size:15px;">
                    📩 <strong>ศูนย์ช่วยเหลือ:</strong>
                    <a href="mailto:admin@snru.ac.th" style="color:#2563eb; text-decoration:none;">thawirat.la63@snru.ac.th</a>
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
