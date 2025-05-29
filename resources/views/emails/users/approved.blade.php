<x-mail::message>
    # สวัสดี {{ $user->name }}

    บัญชีของคุณได้รับการอนุมัติเรียบร้อยแล้ว
    ขอบคุณที่สมัครใช้งานกับเรา

    <x-mail::button :url="url('/')">
        เข้าสู่ระบบ
    </x-mail::button>

    ขอบคุณ,<br>
    {{ config('app.name') }}
</x-mail::message>
