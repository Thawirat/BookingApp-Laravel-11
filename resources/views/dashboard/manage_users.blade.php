@extends('layouts.app')

@section('content')
    <div>
        <div class="col-md-10 content">
            <!-- ส่วนหัว -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>จัดการผู้ใช้</h2>
                <div class="d-flex align-items-center">
                    <form action="{{ route('manage_users.index') }}" method="GET" class="d-flex">
                        <input class="search-bar" placeholder="ค้นหาผู้ใช้" type="text" name="search"
                            value="{{ request('search') }}" />
                        <button type="submit" class="icon-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- สถิติต่าง ๆ -->
            <div class="row mb-4">
                <!-- จำนวนผู้ใช้ทั้งหมด -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-users icon"></i>
                        <div class="details">
                            <h3>{{ $totalUsers }}</h3>
                            <p>จำนวนผู้ใช้ทั้งหมด</p>
                        </div>
                    </div>
                </div>

                <!-- จำนวนผู้ดูแลระบบ -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-user-shield icon"></i>
                        <div class="details">
                            <h3>{{ $adminCount }}</h3>
                            <p>จำนวนผู้ใช้ระบบ</p>
                        </div>
                    </div>
                </div>

                <!-- จำนวนผู้ใช้ทั่วไป -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-user icon"></i>
                        <div class="details">
                            <h3>{{ $regularUserCount }}</h3>
                            <p>จำนวนผู้ใช้ทั่วไป</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางแสดงผู้ใช้ -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>รายการผู้ใช้</h5>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับที่</th>
                                        <th class="text-center">ชื่อผู้ใช้</th>
                                        <th class="text-center">อีเมล</th>
                                        <th class="text-center">บทบาท</th>
                                        <th class="text-center">การกระทำ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role === 'admin')
                                                    <span class="badge bg-primary">ผู้ดูแลระบบหลัก</span>
                                                @elseif($user->role === 'sub-admin')
                                                    <span class="badge bg-info">ผู้ดูแลอาคาร</span>
                                                @else
                                                    <span class="badge bg-secondary">ผู้ใช้ทั่วไป</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- แก้ไขผู้ใช้ -->
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="openEditUserModal({{ $user->id }})">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </button>

                                                <!-- ลบผู้ใช้ -->
                                                <form action="{{ route('manage_users.destroy', $user->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('แน่ใจไม่ต้องการลบผู้ใช้นี้?')">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    /* Main Layout Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f7;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .content {
        margin: 0 auto;
    }

    /* Header Styles */
    h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    /* Search Bar */
    .search-bar {
        border: none;
        background-color: #fff;
        border-radius: 30px;
        padding: 10px 15px;
        width: 200px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        margin-right: 10px;
    }

    .icon-btn {
        background-color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .icon-btn:hover {
        background-color: #f8f8f8;
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 15px;
        object-fit: cover;
    }

    /* Stat Cards */
    .stat-card {
        background-color: #fff;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        height: 100px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card .icon {
        font-size: 24px;
        background-color: #FFC107;
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        margin-right: 15px;
    }

    .stat-card .details h3 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }

    .stat-card .details p {
        margin: 5px 0 0;
        color: #777;
        font-size: 14px;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }

    .card-header h5 {
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .card-body {
        padding: 20px;
    }

    /* Table Styles */
    .table {
        width: 100%;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #555;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 12px 8px;
        background-color: #f9f9f9;
    }

    .table td {
        padding: 12px 8px;
        vertical-align: middle;
        border-top: 1px solid #eee;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    /* Button Styles */
    .btn {
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .btn-success {
        background-color: #FFC107;
        border-color: #FFC107;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #e0a800;
        border-color: #e0a800;
    }

    .btn-danger {
        background-color: #F44336;
        border-color: #F44336;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    /* Custom Style for Status */
    td:nth-child(8) {
        font-weight: 600;
    }

    /* Custom Style for Different Status */
    td:nth-child(8):contains('approved') {
        color: #4CAF50;
    }

    td:nth-child(8):contains('pending') {
        color: #FFC107;
    }

    td:nth-child(8):contains('rejected') {
        color: #F44336;
    }

    /* Custom Style for Payment Status */
    td:nth-child(9) {
        font-weight: 600;
    }

    td:nth-child(9):contains('paid') {
        color: #4CAF50;
    }

    td:nth-child(9):contains('unpaid') {
        color: #F44336;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }

    /* Add these new styles */
    .badge {
        padding: 0.5em 0.8em;
        font-size: 0.8em;
        font-weight: 500;
        border-radius: 30px;
    }

    .bg-primary {
        background-color: #0d6efd !important;
        color: white;
    }

    .bg-info {
        background-color: #0dcaf0 !important;
        color: white;
    }

    .bg-secondary {
        background-color: #6c757d !important;
        color: white;
    }

    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #eee;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #444;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0.6rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">แก้ไขข้อมูลผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">รหัสผ่านใหม่
                            (เว้นว่างถ้าไม่ต้องการเปลี่ยน)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">บทบาท</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">ผู้ดูแลระบบหลัก</option>
                            <option value="sub-admin">ผู้ดูแลอาคาร</option>
                            <option value="user">ผู้ใช้ทั่วไป</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อาคารที่ดูแล</label>
                        <div id="buildings_container" class="border p-3 rounded">
                            <!-- Buildings will be dynamically loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        window.openEditUserModal = function(userId) {
            fetch(`/api/users/${userId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(user => {
                    document.getElementById('editUserForm').action = `/manage-users/${userId}`;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role').value = user.role;
                    document.getElementById('edit_password').value = '';

                    // Trigger toggle after setting role
                    toggleBuildingsField();

                    // Load buildings
                    loadUserBuildings(userId);

                    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user data: ' + error.message);
                });
        };

        function loadUserBuildings(userId) {
            fetch(`/api/users/${userId}/buildings`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('buildings_container');
                    container.innerHTML = data.buildings.map(building => `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="buildings[]"
                           value="${building.id}"
                           id="building_${building.id}"
                           ${building.assigned ? 'checked' : ''}>
                    <label class="form-check-label" for="building_${building.id}">
                        ${building.building_name}
                    </label>
                </div>
            `).join('');
                });
        }

        function toggleBuildingsField() {
            const roleSelect = document.getElementById('edit_role');
            const buildingsContainer = document.getElementById('buildings_container');
            const buildingsWrapper = buildingsContainer.closest('.mb-3');
            const selectedRole = roleSelect.value;

            // แสดงเฉพาะเมื่อเป็น sub-admin เท่านั้น
            if (selectedRole === 'sub-admin') {
                buildingsWrapper.style.display = 'block';
            } else {
                buildingsWrapper.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('edit_role');
            if (roleSelect) {
                roleSelect.addEventListener('change', toggleBuildingsField);
            }
        });
    </script>
@endpush
