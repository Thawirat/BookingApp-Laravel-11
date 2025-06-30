@component('mail::message')
# สวัสดี {{ $user->name }}

บัญชีของคุณได้รับการอนุมัติเรียบร้อยแล้ว
ขอบคุณที่สมัครใช้งานกับเรา

@component('mail::button', ['url' => url('/')])
เข้าสู่ระบบ
@endcomponent

ขอบคุณ,<br>
{{ config('app.name') }}
@endcomponent
