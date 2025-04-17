@extends('layouts.app')

@section('content')
<div class="row" id="buildings-container">
    <div class="col-md-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">รายการอาคาร</h5>
                @if(Auth::user()->role === 'admin')
                <button class="btn btn-primary btn-sm" onclick="openAddBuildingModal()">
                    <i class="fas fa-plus me-1"></i>่มอาคาร
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 p-4">
                    @foreach($buildings as $building)
                        @if(Auth::user()->role === 'admin' ||
                            (Auth::user()->role === 'sub-admin' && $building->users->contains(Auth::id())))
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="position-relative">
                                        @if($building->image)
                                            <img src="{{ asset('storage/' . $building->image) }}"
                                                 class="card-img-top"
                                                 alt="{{ $building->building_name }}"
                                                 style="height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-light" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-building fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title mb-1">{{ $building->building_name }}</h6>
                                            <p class="card-text small text-muted mb-0">
                                                <i class="fas fa-user me-1"></i>{{ $building->citizen_save }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white border-0 d-flex gap-2 pt-0">
                                            <button class="btn btn-sm btn-outline-warning flex-grow-1"
                                                    onclick="openEditBuildingModal('{{ $building->id }}', '{{ $building->building_name }}', '{{ $building->citizen_save }}')">
                                                <i class="fas fa-edit me-1"></i>แก้ไข
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger flex-grow-1"
                                                    onclick="confirmDeleteBuilding('{{ $building->id }}')">
                                                <i class="fas fa-trash me-1"></i>ลบ
                                            </button>
                                            <button class="btn btn-sm btn-outline-info flex-grow-1"
                                                    onclick="window.location.href='{{ route('sub_admin.rooms', $building->id) }}'">
                                                <i class="fas fa-door-open me-1"></i>ห้อง
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="d-flex justify-content-center p-4">
                    {{ $buildings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Building Modal -->
<div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBuildingModalLabel">่มอาคาร</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
<form id="addBuildingForm" action="{{ route('manage.buildings.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    <div class="form-group">
                        <label for="building_name">ชื่ออาคาร</label>
                        <input type="text" class="form-control" id="building_name" name="building_name" required>
                    </div>
                    <div class="form-group">
                        <label for="citizen_save">ชื่อ</label>
                        <input type="text" class="form-control" id="citizen_save" name="citizen_save" required>
                    </div>
                    <div class="form-group">
                        <label for="building_image">ภาพอาคาร</label>
                        <input type="file" class="form-control" id="building_image" name="image" accept="image/*">
                        <small class="form-text text-muted">ไฟล์ภาพ (jpeg, png, jpg, gif) ขนาดไม่ 2MB</small>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addBuildingForm').submit();">่มอาคาร</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Building Modal -->
<div class="modal fade" id="editBuildingModal" tabindex="-1" role="dialog" aria-labelledby="editBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBuildingModalLabel">แก้ไขอาคาร</h5>
                <button type="button" class="close" data-dismiss="modal="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
<form id="editBuildingForm" action="" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_building_name">ชื่ออาคาร</label>
                        <input type="text" class="form-control" id="edit_building_name" name="building_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_citizen_save">โดย</label>
                        <input type="text" class="form-control" id="edit_citizen_save" name="citizen_save" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_building_image">ภาพอาคาร</label>
                        <input type="file" class="form-control" id="edit_building_image" name="image" accept="image/*">
                        <small class="form-text text-muted">ไฟล์ภาพ (jpeg, png, jpg, gif) ขนาดไม่ 2MB</small>
                        <div id="currentImage" class="mt-2"></div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('editBuildingForm').submit()">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">การลบ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                แน่ใจไม่จะลบอาคารหรือไม่?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <form id="deleteForm" action="" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">ลบ</button>
                </form>
            </div>
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

function confirmDeleteBuilding(id) {
    // Set form action
    document.getElementById('deleteForm').action = `/manage/buildings/${id}`;
    // Show Modal
    $('#deleteConfirmationModal').modal('show');
}
</script>

@endsection



