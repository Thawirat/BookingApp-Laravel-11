@extends('layouts.app')

@section('content')
    <div>
        <div class="col-md-10 content">
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
            @include('components.manage-user-card')
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>รายการผู้ใช้</h5>
                                </div>

                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับที่</th>
                                                <th class="text-center">ชื่อ-นามสกุล</th>
                                                <th class="text-center">หน่วยงาน</th>
                                                <th class="text-center">อีเมล</th>
                                                <th class="text-center">เบอร์โทรศัพท์</th>
                                                <th class="text-center">บทบาท</th>
                                                <th class="text-center">สถานะ</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="text-center">{{ $user->name }}</td>
                                                    <td class="text-center">{{ $user->department }}</td>
                                                    <td class="text-center">{{ $user->email }}</td>
                                                    <td class="text-center">{{ $user->phone_number }}</td>
                                                    <td class="text-center">
                                                        @if ($user->role === 'admin')
                                                            <span class="badge bg-primary">ผู้ดูแลระบบหลัก</span>
                                                        @elseif($user->role === 'sub-admin')
                                                            <span class="badge bg-info text-white">ผู้ดูแลอาคาร</span>
                                                        @else
                                                            <span class="badge bg-secondary">ผู้ใช้ทั่วไป</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @include('components.dropdown.user-dropdown')
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                onclick="openEditUserModal({{ $user->id }})">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @include('components.user-edit')
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-user-btn"
                                                                data-id="{{ $user->id }}"
                                                                data-name="{{ $user->name }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <form id="deleteUserForm{{ $user->id }}"
                                                            action="{{ route('manage_users.destroy', $user->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
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
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .content {
        margin: 0 auto;
    }
</style>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'ยืนยันการลบ',
                        html: `คุณแน่ใจหรือไม่ว่าต้องการลบ <strong>${userName}</strong>?`, // ใช้ html: แทน text:
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'ลบ',
                        cancelButtonText: 'ยกเลิก',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`deleteUserForm${userId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
