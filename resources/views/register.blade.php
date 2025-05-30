<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>สมัครสมาชิก - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-image: url('{{ asset('images/bg-1.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">สมัครสมาชิก</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700" for="name">ชื่อ-นามสกุล</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="name" name="name" type="text" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700" for="department">หน่วยงาน</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="department" name="department" type="text" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700" for="phone_number">เบอร์โทรศัพท์</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="phone_number" name="phone_number" type="tel" required pattern="[0-9]{10}" maxlength="10"
                    title="กรุณาใส่เบอร์โทรศัพท์ 10 หลัก" />
                <p id="phoneError" class="text-sm text-red-600 mt-1 hidden">กรุณากรอกเบอร์โทร 10 หลัก</p>
            </div>

            <div>
                <label id="emailLabel" class="block text-sm font-medium text-gray-700" for="email">อีเมล</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="email" name="email" type="email" required />
                <p id="emailError" class="text-sm text-red-600 mt-1 hidden">อีเมลต้องลงท้ายด้วย @snru.ac.th เท่านั้น</p>
            </div>
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700" for="password">รหัสผ่าน</label>
                <input
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                    id="password" name="password" type="password" required />
                <span class="absolute right-3 top-7 cursor-pointer text-gray-500"
                    onclick="togglePasswordVisibility('password', this)">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <div class="relative mt-4">
                <label class="block text-sm font-medium text-gray-700"
                    for="password_confirmation">ยืนยันรหัสผ่าน</label>
                <input
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                    id="password_confirmation" name="password_confirmation" type="password" required />
                <span class="absolute right-3 top-7 cursor-pointer text-gray-500"
                    onclick="togglePasswordVisibility('password_confirmation', this)">
                    <i class="fas fa-eye"></i>
                </span>
                <p id="passwordMatchError" class="text-sm text-red-600 mt-1 hidden">รหัสผ่านไม่ตรงกัน</p>
            </div>
            <div>
                <button
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300"
                    type="submit">สมัครสมาชิก</button>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-600">มีบัญชีอยู่แล้ว? <a class="text-blue-500 hover:underline"
                href="{{ url('/login') }}">เข้าสู่ระบบ</a></p>
        <p class="mt-6 text-center text-gray-600"><a class="text-blue-500 hover:underline"
                href="{{ url('/') }}">กลับสู่หน้าหลัก</a></p>
    </div>
</body>
<script>
    function togglePasswordVisibility(id, iconElement) {
        const input = document.getElementById(id);
        const icon = iconElement.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");

        const phoneInput = document.getElementById("phone_number");
        const phoneError = document.getElementById("phoneError");

        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("password_confirmation");
        const passwordError = document.getElementById("passwordMatchError");
        const form = document.querySelector("form");

        // Email validation
        emailInput.addEventListener("input", function() {
            const email = emailInput.value.trim();
            const valid = /^[a-zA-Z0-9._%+-]+@snru\.ac\.th$/i.test(email);

            if (!valid && email !== "") {
                emailInput.classList.add("border-red-500");
                emailError.classList.remove("hidden");
            } else {
                emailInput.classList.remove("border-red-500");
                emailError.classList.add("hidden");
            }
        });

        // Phone number validation
        phoneInput.addEventListener("input", function() {
            const phone = phoneInput.value.trim();
            const valid = /^[0-9]{10}$/.test(phone);

            if (!valid && phone !== "") {
                phoneInput.classList.add("border-red-500");
                phoneError.classList.remove("hidden");
            } else {
                phoneInput.classList.remove("border-red-500");
                phoneError.classList.add("hidden");
            }
        });

        // Password match validation
        function checkPasswordMatch() {
            if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value !== "") {
                confirmPasswordInput.classList.add("border-red-500");
                passwordError.classList.remove("hidden");
            } else {
                confirmPasswordInput.classList.remove("border-red-500");
                passwordError.classList.add("hidden");
            }
        }

        passwordInput.addEventListener("input", checkPasswordMatch);
        confirmPasswordInput.addEventListener("input", checkPasswordMatch);

        form.addEventListener("submit", function(e) {
            let hasError = false;

            // ตรวจสอบอีเมล
            const email = emailInput.value.trim();
            if (!/^[a-zA-Z0-9._%+-]+@snru\.ac\.th$/i.test(email)) {
                emailInput.classList.add("border-red-500");
                emailError.classList.remove("hidden");
                hasError = true;
            }

            // ตรวจสอบเบอร์โทร
            const phone = phoneInput.value.replace(/\D/g, '');
            if (!/^[0-9]{10}$/.test(phone)) {
                phoneInput.classList.add("border-red-500");
                phoneError.classList.remove("hidden");
                hasError = true;
            }

            // ตรวจสอบรหัสผ่านตรงกัน
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add("border-red-500");
                passwordError.classList.remove("hidden");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault(); // ป้องกันการ submit ฟอร์ม
            }
        });
    });
</script>

</html>
