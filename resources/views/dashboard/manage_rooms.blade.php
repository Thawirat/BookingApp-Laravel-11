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
                    <div class="container mx-auto pb-3 py-3">
                        <div class="grid grid-cols-4 gap-4">
                            @include('components.building-card')
                        </div>
                    </div>
                    <div class="d-flex justify-content-center p-4">
                        {{ $buildings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.modal.buildings.add-building')
    @include('components.modal.buildings.edit-building')

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
        document.getElementById('building_image').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('addPreviewImage');
            previewContainer.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.classList.add('img-fluid', 'rounded', 'shadow-sm');
                img.style.maxHeight = '200px';
                previewContainer.appendChild(img);
            }
        });
    </script>
@endsection
