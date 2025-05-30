<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>รีเซ็ตรหัสผ่าน - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
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

<body class="bg-gray-900 bg-opacity-90 flex items-center justify-center min-h-screen px-4">
    <div
        class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md transform transition-all hover:scale-[1.01] duration-300 ease-in-out">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">รีเซ็ตรหัสผ่าน</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">อีเมล</label>
                <input id="email" type="email" name="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    value="{{ old('email', $email ?? '') }}" required autocomplete="email">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">รหัสผ่านใหม่</label>
                <input id="password" type="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    required autocomplete="new-password">
                <span onclick="togglePassword('password', 'togglePasswordBtn')" id="togglePasswordBtn"
                    class="absolute right-3 top-9 cursor-pointer text-gray-500 hover:text-gray-700">
                    <i class="far fa-eye" id="passwordEyeIcon"></i>
                </span>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4 relative">
                <label for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 mb-2">ยืนยันรหัสผ่าน</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="new-password">
                <span onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordBtn')"
                    id="toggleConfirmPasswordBtn"
                    class="absolute right-3 top-9 cursor-pointer text-gray-500 hover:text-gray-700">
                    <i class="far fa-eye" id="confirmPasswordEyeIcon"></i>
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition transform hover:shadow-md hover:-translate-y-0.5 duration-200 ease-in-out">
                เปลี่ยนรหัสผ่าน
            </button>
        </form>
    </div>

    <script>
        function togglePassword(inputId, btnId) {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`#${btnId} i`);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
