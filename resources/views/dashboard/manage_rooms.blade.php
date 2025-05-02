@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการห้องและอาคาร</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('manage_rooms.index') }}" method="GET" class="d-flex">
                    <input class="search-bar" name="search" placeholder="ค้นหาอาคาร" type="text"
                        value="{{ request('search') }}" />
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card" onclick="showBuildings()">
                    <i class="fas fa-building icon"></i>
                    <div class="details">
                        <h3>{{ $totalBuildings }}</h3>
                        <p>จำนวนอาคารทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-door-closed icon"></i>
                    <div class="details">
                        <h3>{{ $totalRooms }}</h3>
                        <p>ห้องทั้งหมดในระบบ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" onclick="showAvailableRooms()">
                    <i class="fas fa-door-open icon"></i>
                    <div class="details">
                        <h3>{{ $rooms->where('status_id', '2')->count() }}</h3>
                        <p>ห้องที่ใช้งานได้</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" onclick="showUnavailableRooms()">
                    <i class="fas fa-door-closed icon"></i>
                    <div class="details">
                        <h3>{{ $rooms->where('status_id', '1')->count() }}</h3>
                        <p>ห้องที่ใช้งานไม่ได้</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="buildings-container">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">รายการอาคาร</h5>
                        @if (Auth::user()->role === 'admin')
                            <button class="btn btn-primary btn-sm" onclick="openAddBuildingModal()">
                                <i class="fas fa-plus me-1"></i>เพิ่มอาคาร
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 p-4">
                            @foreach ($buildings as $building)
                                <div class="col">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="position-relative">
                                            <img alt="ภาพ{{ $building->building_name }}" class="card-img-top"
                                                src="{{ $building->image ? asset('storage/' . $building->image) : asset('images/no-picture.jpg') }}"
                                                style="height: 180px; object-fit: cover;" />
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-door-open me-1"></i>{{ $building->rooms->count() }}
                                                    ห้อง
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $building->building_name }}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-user-edit me-1"></i>บันทึกโดย:
                                                {{ $building->citizen_save }}
                                            </p>
                                            <div class="d-flex gap-2">
                                                @if (Auth::user()->role === 'admin')
                                                    <button class="btn btn-sm btn-outline-warning flex-grow-1"
                                                        onclick="openEditBuildingModal('{{ $building->id }}', '{{ $building->building_name }}', '{{ $building->citizen_save }}')">
                                                        <i class="fas fa-edit me-1"></i>แก้ไข
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger flex-grow-1"
                                                        onclick="confirmDeleteBuilding('{{ $building->id }}', '{{ $building->building_name }}')">
                                                        <i class="fas fa-trash me-1"></i>ลบ
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-info flex-grow-1"
                                                    onclick="window.location.href='{{ route('manage_rooms.show', $building->id) }}'">
                                                    <i class="fas fa-door-open me-1"></i>ดูห้อง
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center p-4">
                            {{ $buildings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 Modal: Add Building -->
    <div class="modal fade" id="addBuildingModal" tabindex="-1" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow rounded-4">
                <form id="addBuildingForm" action="{{ route('manage.buildings.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-3">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="addBuildingModalLabel">เพิ่มอาคาร</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="building_name" class="form-label">ชื่ออาคาร</label>
                            <input type="text" class="form-control" id="building_name" name="building_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="citizen_save" class="form-label">ชื่อผู้บันทึก</label>
                            <input type="text" class="form-control" id="citizen_save" name="citizen_save" required>
                        </div>

                        <div class="mb-3">
                            <label for="building_image" class="form-label">อัปโหลดรูปภาพอาคาร</label>
                            <input type="file" class="form-control" id="building_image" name="image"
                                accept="image/*">
                            <small class="form-text text-muted">รองรับไฟล์ jpeg, png, gif (ไม่เกิน 2MB)</small>
                            <div id="addPreviewImage" class="mt-2"></div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">เพิ่มอาคาร</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Modal: Edit Building -->
    <div class="modal fade" id="editBuildingModal" tabindex="-1" aria-labelledby="editBuildingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow rounded-4">
                <form id="editBuildingForm" action="" method="POST" enctype="multipart/form-data" class="p-3">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editBuildingModalLabel">แก้ไขอาคาร</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="edit_building_name" class="form-label">ชื่ออาคาร</label>
                            <input type="text" class="form-control" id="edit_building_name" name="building_name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_citizen_save" class="form-label">ชื่อผู้บันทึก</label>
                            <input type="text" class="form-control" id="edit_citizen_save" name="citizen_save"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_building_image" class="form-label">อัปโหลดรูปภาพอาคาร</label>
                            <input type="file" class="form-control" id="edit_building_image" name="image"
                                accept="image/*">
                            <small class="form-text text-muted">หากไม่ต้องการเปลี่ยนภาพ ให้เว้นว่างไว้</small>
                            <div id="currentImage" class="mt-2"></div>
                            <div id="editPreviewImage" class="mt-2"></div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddBuildingModal() {
            $('#addBuildingModal').modal('show');
        }

        function openEditBuildingModal(id, name, citizen_save) {
            // Set form action
            document.getElementById('editBuildingForm').action = `/manage/buildings/${id}`;
            // Fill in existing data
            document.getElementById('edit_building_name').value = name;
            document.getElementById('edit_citizen_save').value = citizen_save;
            // Show Modal
            $('#editBuildingModal').modal('show');
        }

        function confirmDeleteBuilding(id, name) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: `คุณต้องการลบ "${name}" ใช่หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/manage/buildings/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
