@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-12 space-y-10 px-4">
        <!-- Profile Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mx-auto max-w-lg">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">โปรไฟล์ของคุณ</h2>

            <div class="flex flex-col items-center text-center mb-6">
                <img src="{{ Auth::user()->avatar_url ?? asset('images/profile-avatar.png') }}" alt="User Avatar"
                    class="w-36 h-36 rounded-full border-4 border-blue-500 object-cover shadow-lg mb-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name ?? 'Guest' }}</h3>
                <p class="text-gray-500 text-sm mt-1">บัญชีผู้ใช้งาน</p>
            </div>

            <!-- Contact Information -->
            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">ข้อมูลการติดต่อ</h3>

                <div class="flex justify-between items-center">
                    <span><strong>Email:</strong> {{ Auth::user()->email }}</span>
                    <a href="#" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i></a>
                </div>

                <div class="flex justify-between items-center">
                    <span><strong>เบอร์โทร:</strong> {{ Auth::user()->phone ?? 'ยังไม่ได้เพิ่มข้อมูล' }}</span>
                    <a href="#" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i></a>
                </div>

                <div class="flex justify-between items-center">
                    <span><strong>ที่อยู่:</strong> {{ Auth::user()->address ?? 'ยังไม่ได้เพิ่มข้อมูล' }}</span>
                    <a href="#" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i></a>
                </div>

                <div class="flex justify-between items-center">
                    <span><strong>วันเกิด:</strong> {{ Auth::user()->dob ?? 'ยังไม่ได้เพิ่มข้อมูล' }}</span>
                    <a href="#" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i></a>
                </div>
            </section>
        </div>

        <!-- Change Password Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mx-auto max-w-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">เปลี่ยนรหัสผ่าน</h3>

            <form action="{{ route('user.changePassword') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-4">
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านเดิม</label>
                    <input type="password" id="currentPassword" name="current_password" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="space-y-4">
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่</label>
                    <input type="password" id="newPassword" name="new_password" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="space-y-4">
                    <label for="confirmPassword"
                        class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" id="confirmPassword" name="confirm_password" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="text-right pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition">
                        บันทึกรหัสผ่านใหม่
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
