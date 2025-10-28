<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>เข้าสู่ระบบ - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" href="{{ asset('favicon_io/favicon-32x32.png') }}" type="image/png">
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background-image: linear-gradient(to bottom right, rgba(137, 255, 166, 0.4), rgba(160, 183, 245, 0.6), rgba(245, 160, 234, 0.6)), url('{{ asset('images/bg-1.jpg') }}');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">
  <div class="backdrop-blur-md bg-white/90 p-10 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 transform transition-all hover:scale-[1.01] duration-300 ease-in-out">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-blue-700">เข้าสู่ระบบ</h2>
      <p class="text-gray-500 text-sm mt-1">ระบบจองห้องออนไลน์ของมหาวิทยาลัย</p>
    </div>

    @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    @if (session('status'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
        {{ session('status') }}
      </div>
    @endif

    <form class="space-y-5" action="{{ route('login.post') }}" method="POST">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
        <input id="email" name="email" type="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div class="relative">
        <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
        <input id="password" name="password" type="password" required
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" />
        <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePasswordVisibility('password', this)">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center">
          <input id="remember_me" name="remember_me" type="checkbox"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
          <span class="ml-2 text-gray-700">จดจำฉัน</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500">ลืมรหัสผ่าน?</a>
      </div>

      <button type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 ease-in-out">
        เข้าสู่ระบบ
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600 space-y-2">
      <p>ยังไม่มีบัญชี? <a href="{{ url('/register') }}" class="text-blue-600 font-medium hover:underline">สมัครสมาชิก</a></p>
    </div>
  </div>

  <script>
    function togglePasswordVisibility(id, el) {
      const input = document.getElementById(id);
      const icon = el.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>
</body>
</html>
