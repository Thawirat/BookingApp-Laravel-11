@extends('layouts.app')

@section('content')
    <div class="container py-5 px-3 px-md-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Profile Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>โปรไฟล์ของคุณ</h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ Auth::user()->avatar_url ?? asset('images/profile-avatar2.png') }}" alt="User Avatar"
                            class="rounded-circle mb-3 mx-auto d-block"
                            style="width: 130px; height: 130px;">
                        <h5 class="mb-0">{{ Auth::user()->name ?? 'Guest' }}</h5>
                        <p class="text-muted small">บัญชีผู้ใช้งาน</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleEdit()">แก้ไขโปรไฟล์</button>
                    </div>

                </div>

                <!-- Editable Profile Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body ps-3 pe-3 pt-3 pb-3">
                        <form method="POST" action="{{ route('user.updateAll') }}" enctype="multipart/form-data"
                            id="profileForm">
                            @csrf
                            @method('PUT')

                            <!-- Profile Info -->
                            <h5 class="text-primary"><i class="fas fa-user-edit me-2"></i>ข้อมูลโปรไฟล์</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ชื่อผู้ใช้</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ประเภทผู้ใช้งาน</label>
                                    <select name="user_type" class="form-select" disabled>
                                        <option value="internal"
                                            {{ Auth::user()->user_type == 'internal' ? 'selected' : '' }}>บุคคลภายใน
                                        </option>
                                        <option value="external"
                                            {{ Auth::user()->user_type == 'external' ? 'selected' : '' }}>บุคคลภายนอก
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ตำแหน่ง</label>
                                    <input type="text" name="position" value="{{ Auth::user()->position }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <hr>

                            <!-- Contact Info -->
                            <h5 class="text-primary"><i class="fas fa-address-book me-2"></i>ข้อมูลการติดต่อ</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">หน่วยงาน</label>
                                    <input type="text" name="department" value="{{ Auth::user()->department }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">เบอร์โทร</label>
                                    <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ที่อยู่</label>
                                    <input type="text" name="address" value="{{ Auth::user()->address }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <hr>

                            <!-- Password -->
                            <h5 class="text-primary"><i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน
                                (ไม่กรอกหากไม่ต้องการเปลี่ยน)</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">รหัสผ่านเดิม</label>
                                    <input type="password" name="current_password" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">รหัสผ่านใหม่</label>
                                    <input type="password" name="new_password" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" name="confirm_password" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="editButtons" class="mt-4 d-none text-end">
                                <button type="submit" class="btn btn-warning me-2">บันทึกการเปลี่ยนแปลง</button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEdit(false)">ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        function toggleEdit(enable = true) {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input');
            const editButtons = document.getElementById('editButtons');
            const selects = form.querySelectorAll('select');

            selects.forEach(select => select.disabled = !enable);
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
