<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ลืมรหัสผ่าน - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"  rel="stylesheet" />

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
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md transform transition-all hover:scale-[1.01] duration-300 ease-in-out">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">ลืมรหัสผ่าน</h1>

        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">อีเมล</label>
                <input id="email" type="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition transform hover:shadow-md hover:-translate-y-0.5 duration-200 ease-in-out">
                ส่งลิงก์รีเซ็ตรหัสผ่าน
            </button>
        </form>

        <!-- Back to login -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 transition">
                &larr; กลับไปเข้าสู่ระบบ
            </a>
        </div>
    </div>
</body>

</html>
