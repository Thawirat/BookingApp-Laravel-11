@extends('layouts.app')

@section('content')
    <div class="container mt-5">

        <!-- Profile Card -->
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h2><i class="fas fa-user-circle me-2 text-primary"></i> โปรไฟล์ของคุณ</h2>
            </div>
            <div class="card-body text-center">
                <img src="{{ Auth::user()->avatar_url ?? asset('images/profile-avatar.png') }}" alt="User Avatar"
                    class="rounded-circle border-4 border-primary mb-3" style="width: 150px; height: 150px;">
                <h3>{{ Auth::user()->name ?? 'Guest' }}</h3>
                <p>บัญชีผู้ใช้งาน</p>
                <button class="btn btn-outline-primary" onclick="toggleEdit()">แก้ไขโปรไฟล์</button>
            </div>
        </div>

        <!-- Editable Profile Section -->
        <div class="card mt-4">
            <div class="card-body">
                <form method="POST" action="{{ route('user.updateAll') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')

                    <!-- ข้อมูลโปรไฟล์ -->
                    <h5 class="mb-3"><i class="fas fa-user-edit me-2 text-primary"></i>ข้อมูลโปรไฟล์</h5>

                    <div class="mb-3">
                        <label class="form-label d-flex align-items-center ps-2">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <span>ชื่อผู้ใช้</span>
                        </label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-users me-2 text-primary"></i>ประเภทผู้ใช้งาน</label>
                        <select name="user_type" class="form-select" disabled>
                            <option value="internal" {{ Auth::user()->user_type == 'internal' ? 'selected' : '' }}>
                                บุคลากรภายใน</option>
                            <option value="external" {{ Auth::user()->user_type == 'external' ? 'selected' : '' }}>
                                บุคคลภายนอก</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-briefcase me-2 text-primary"></i>ตำแหน่ง</label>
                        <input type="text" name="position" value="{{ Auth::user()->position }}" class="form-control"
                            readonly>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="fas fa-address-book me-2 text-primary"></i>ข้อมูลการติดต่อ</h5>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-envelope me-2 text-primary"></i>Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-building me-2 text-primary"></i>หน่วยงาน</label>
                        <input type="text" name="department" value="{{ Auth::user()->department }}" class="form-control"
                            readonly>
                    </div>

                    <!-- เบอร์โทร (แก้ชื่อ field จาก phone เป็น phone_number) -->
                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-phone-alt me-2 text-primary"></i>เบอร์โทร</label>
                        <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}"
                            class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i
                                class="fas fa-map-marker-alt me-2 text-primary"></i>ที่อยู่</label>
                        <input type="text" name="address" value="{{ Auth::user()->address }}" class="form-control"
                            readonly>
                    </div>
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="fas fa-key me-2 text-primary"></i>เปลี่ยนรหัสผ่าน
                        (ไม่กรอกหากไม่ต้องการเปลี่ยน)</h5>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-lock me-2 text-primary"></i>รหัสผ่านเดิม</label>
                        <input type="password" name="current_password" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i class="fas fa-key me-2 text-primary"></i>รหัสผ่านใหม่</label>
                        <input type="password" name="new_password" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ms-1"><i
                                class="fas fa-check-circle me-2 text-primary"></i>ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="confirm_password" class="form-control" readonly>
                    </div>

                    <div id="editButtons" class="d-none">
                        <button type="submit" class="btn btn-success mt-3 me-2">บันทึกการเปลี่ยนแปลง</button>
                        <button type="button" class="btn btn-secondary mt-3" onclick="toggleEdit(false)">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit(enable = true) {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input');
            const editButtons = document.getElementById('editButtons');
            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.disabled = !enable;
            });
            inputs.forEach(input => {
                if (input.type === 'file') {
                    input.disabled = !enable;
                } else {
                    input.readOnly = !enable;
                }
            });

            editButtons.classList.toggle('d-none', !enable);
        }
    </script>
@endsection
