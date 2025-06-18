@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>จัดการห้องในอาคาร: {{ $building->building_name }}</h2>
                <div class="d-flex align-items-center">
                    <form action="{{ route('manage_rooms.show', $building->id) }}" method="GET" class="d-flex">
                        <input class="search-bar" placeholder="ค้นหาห้อง" type="text" name="search"
                            value="{{ request('search') }}" />
                        <button type="submit" class="icon-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-building icon"></i>
                        <div class="details">
                            <h3>{{ $totalCount }}</h3>
                            <p>ห้องทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-door-open icon"></i>
                        <div class="details">
                            <h3>{{ $availableCount }}</h3>
                            <p>ห้องที่ใช้งานได้</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-door-closed icon"></i>
                        <div class="details">
                            <h3>{{ $unavailableCount }}</h3>
                            <p>ห้องที่ใช้งานไม่ได้</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="rooms-container">
                <div class="col-md-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0">รายการห้องในอาคาร {{ $building->building_name }}</h5>
                            <div>
                                <a href="{{ route('manage_rooms.index') }}" class="btn btn-secondary btn-sm me-2">
                                    <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าอาคาร
                                </a>
                                <button class="btn btn-primary btn-sm" onclick="openAddRoomModal()">
                                    <i class="fas fa-plus me-1"></i>เพิ่มห้อง
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 p-4">
                                @foreach ($rooms as $room)
                                    <div class="col">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="position-relative">
                                                <img alt="ภาพห้อง {{ $room->room_name }}" class="card-img-top"
                                                    src="{{ $room->image ? asset('storage/' . $room->image) : asset('images/no-picture.jpg') }}"
                                                    style="height: 180px; object-fit: cover;" />
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span
                                                        class="badge bg-{{ $room->status_id == 2 ? 'success' : 'danger' }}">
                                                        {{ $room->status->status_name }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body px-3 py-2">
                                                <h5 class="card-title">{{ $room->room_name }}</h5>
                                                <p class="card-text text-muted small mb-2">
                                                    <i class="fas fa-building me-1"></i>
                                                    {{ $building->building_name }}
                                                </p>
                                                <p class="card-text text-muted small mb-2">
                                                    <i class="fas fa-users me-1"></i>ความจุ: {{ $room->capacity }} คน
                                                </p>
                                                <div class="btn-group w-100" role="group">
                                                    <a href="#" class="btn btn-sm btn-warning flex-grow-1"
                                                        onclick="openEditRoomModal(
                                                       '{{ $room->room_id }}',
                                                       '{{ $room->room_name }}',
                                                       '{{ $room->capacity }}',
                                                       '{{ $room->room_type }}',
                                                        '{{ $room->room_type_other ?? '' }}',
                                                       '{{ $room->room_details }}',
                                                       '{{ $room->image ? asset('storage/' . $room->image) : '' }}',
                                                       '{{ $room->class }}',
                                                       '{{ $room->status_id }}',
                                                   )">
                                                        <i class="fas fa-edit me-1"></i>แก้ไข
                                                    </a>
                                                    <button class="btn btn-sm btn-danger flex-grow-1"
                                                        onclick="confirmDeleteRoom('{{ $room->room_id }}', '{{ $room->room_name }}')">
                                                        <i class="fas fa-trash me-1"></i>ลบ
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center p-4">
                                {{ $rooms->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.modal.rooms.add-room')
    @include('components.modal.rooms.edit-room')
    <script>
        // Handle custom room type for Add Modal
        document.getElementById('add_room_type_select').addEventListener('change', function() {
            const customInput = document.getElementById('add_custom_room_type');
            if (this.value === 'other') {
                customInput.classList.remove('d-none');
                customInput.required = true;
            } else {
                customInput.classList.add('d-none');
                customInput.required = false;
                customInput.value = '';
            }
        });

        // Handle custom room type for Edit Modal
        document.getElementById('edit_room_type_select').addEventListener('change', function() {
            const customInput = document.getElementById('edit_custom_room_type');
            if (this.value === 'other') {
                customInput.classList.remove('d-none');
                customInput.required = true;
            } else {
                customInput.classList.add('d-none');
                customInput.required = false;
                customInput.value = '';
            }
        });

        // Function to open Add Room Modal
        function openAddRoomModal() {
            // Reset form values
            document.getElementById('addRoomForm').reset();
            document.getElementById('add_custom_room_type').classList.add('d-none');
            document.getElementById('add_custom_room_type').required = false;

            // Show modal
            $('#addRoomModal').modal('show');
        }

        // Function to open Edit Room Modal
        function openEditRoomModal(roomId, roomName, capacity, roomTypeName, roomTypeOther, roomDetails, imageUrl,
            roomClass, statusId, equipments = []) {

            // Set form action
            document.getElementById('editRoomForm').action = `/manage_rooms/${roomId}`;

            // Set form values
            $('#edit_room_name').val(roomName);
            $('#edit_capacity').val(capacity);
            $('#edit_room_details').val(roomDetails);
            $('#edit_class').val(roomClass);
            $('#edit_status').val(statusId);

            const select = $('#edit_room_type_select');
            const customInput = $('#edit_custom_room_type');

            if (roomTypeName === 'other') {
                select.val('other');
                customInput.removeClass('d-none').prop('required', true).val(roomTypeOther || '');
            } else {
                select.val(roomTypeName);
                customInput.addClass('d-none').prop('required', false).val('');
            }

            $('#currentImage').html(
                imageUrl ?
                `<img src="${imageUrl}" alt="Current Image" style="max-width: 100%; height: auto;" class="mt-2" />` :
                '<p class="text-muted mt-2">ไม่มีรูปภาพ</p>'
            );

            // --- แก้ไขส่วนการจัดการอุปกรณ์ ---
            const wrapper = document.getElementById('equipment-wrapper');

            // ลบทุกแถวอุปกรณ์เดิม (ยกเว้นแถวแรกที่เป็น template)
            const existingRows = wrapper.querySelectorAll('.equipment-row');
            existingRows.forEach(row => row.remove());

            // Debug: ตรวจสอบข้อมูลอุปกรณ์ที่ได้รับ
            console.log('Equipments received:', equipments);
            console.log('Equipments type:', typeof equipments);
            console.log('Equipments length:', equipments ? equipments.length : 'undefined');

            // แปลง equipments เป็น array ถ้าเป็น string (JSON)
            let equipmentArray = [];
            if (typeof equipments === 'string') {
                try {
                    equipmentArray = JSON.parse(equipments);
                } catch (e) {
                    console.error('Error parsing equipments JSON:', e);
                    equipmentArray = [];
                }
            } else if (Array.isArray(equipments)) {
                equipmentArray = equipments;
            }

            console.log('Processed equipment array:', equipmentArray);

            // สร้างแถวอุปกรณ์ใหม่
            if (equipmentArray && equipmentArray.length > 0) {
                equipmentArray.forEach((eq, index) => {
                    console.log(`Equipment ${index}:`, eq);

                    const newRow = createEquipmentRow();

                    // ตรวจสอบและกำหนดค่าให้กับ input fields
                    const nameInput = newRow.querySelector('input[name="equipment_names[]"]');
                    const noteInput = newRow.querySelector('input[name="equipment_notes[]"]');
                    const quantityInput = newRow.querySelector('input[name="equipment_quantities[]"]');

                    if (nameInput) nameInput.value = eq.name || eq.equipment_name || '';
                    if (noteInput) noteInput.value = eq.note || eq.notes || eq.description || eq.equipment_note ||
                        '';
                    if (quantityInput) quantityInput.value = eq.quantity || eq.equipment_quantity || '';

                    // เพิ่มแถวก่อนปุ่ม "เพิ่มอุปกรณ์"
                    const addButton = wrapper.querySelector('#add-equipment-btn');
                    wrapper.insertBefore(newRow, addButton);
                });
            } else {
                // ถ้าไม่มีอุปกรณ์ ให้สร้างแถวเปล่า 1 แถว
                const emptyRow = createEquipmentRow();
                const addButton = wrapper.querySelector('#add-equipment-btn');
                wrapper.insertBefore(emptyRow, addButton);
            }

            $('#editRoomModal').modal('show');
        }

        // ฟังก์ชันสร้างแถวอุปกรณ์
        function createEquipmentRow() {
            const row = document.createElement('div');
            row.classList.add('row', 'g-2', 'mb-2', 'equipment-row');
            row.innerHTML = `
    <div class="col-md-4">
        <input type="text" name="equipment_names[]" class="form-control"
            placeholder="ชื่ออุปกรณ์" required>
    </div>
    <div class="col-md-4">
        <input type="text" name="equipment_notes[]" class="form-control"
            placeholder="รายละเอียด" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="equipment_quantities[]" class="form-control"
            placeholder="จำนวน" min="1" required>
    </div>
    <div class="col-md-1 d-flex align-items-start">
        <button type="button" class="btn btn-danger btn-sm remove-equipment">
            <i class="fas fa-trash"></i>
        </button>
    </div>
`;
            return row;
        }

        // Event Listeners สำหรับจัดการอุปกรณ์
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('equipment-wrapper');
            const addBtn = document.getElementById('add-equipment-btn');

            // เพิ่มอุปกรณ์
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const newRow = createEquipmentRow();
                    wrapper.insertBefore(newRow, addBtn);
                });
            }

            // ลบอุปกรณ์
            wrapper.addEventListener('click', function(e) {
                if (e.target.closest('.remove-equipment')) {
                    const equipmentRows = wrapper.querySelectorAll('.equipment-row');
                    if (equipmentRows.length > 1) {
                        e.target.closest('.equipment-row').remove();
                    } else {
                        // ถ้าเหลือแถวเดียว ให้เคลียร์ค่าแทนการลบ
                        const row = e.target.closest('.equipment-row');
                        row.querySelectorAll('input').forEach(input => input.value = '');
                    }
                }
            });
        });

        // Handle Add Room Form Submission
        document.getElementById('addRoomForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => Promise.reject(error));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'เพิ่มห้องสำเร็จ',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            $('#addRoomModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || "ไม่ทราบสาเหตุ",
                            confirmButtonText: 'ปิด'
                        });
                    }
                })
                .catch(error => {
                    console.error("Error adding room:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการเพิ่มห้อง',
                        confirmButtonText: 'ปิด'
                    });
                });
        });

        // Handle Edit Room Form Submission
        document.getElementById('editRoomForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => Promise.reject(error));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'อัปเดตห้องสำเร็จ',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            $('#editRoomModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || "ไม่ทราบสาเหตุ",
                            confirmButtonText: 'ปิด'
                        });
                    }
                })
                .catch(error => {
                    console.error("Error updating room:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการอัปเดตห้อง',
                        confirmButtonText: 'ปิด'
                    });
                });
        });

        function confirmDeleteRoom(roomId, roomName) {
            Swal.fire({
                title: 'ยืนยันการลบ',
                text: `คุณแน่ใจหรือไม่ว่าต้องการลบ"${roomName}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/manage_rooms/${roomId}`;

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
