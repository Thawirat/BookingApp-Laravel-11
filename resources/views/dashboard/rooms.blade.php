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
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $room->room_name }}</h5>
                                                <p class="card-text text-muted small mb-2">
                                                    <i class="fas fa-building me-1"></i>
                                                    {{ $building->building_name }}
                                                </p>
                                                <p class="card-text text-muted small mb-2">
                                                    <i class="fas fa-users me-1"></i>ความจุ: {{ $room->capacity }} คน
                                                </p>
                                                <div class="d-flex gap-2">
                                                    <a href="#" class="btn btn-sm btn-outline-warning flex-grow-1"
                                                        onclick="openEditRoomModal(
                                                       '{{ $room->room_id }}',
                                                       '{{ $room->room_name }}',
                                                       '{{ $room->capacity }}',
                                                       '{{ $room->room_type }}',
                                                        '{{ $room->room_type_other ?? '' }}',
                                                       '{{ $room->room_details }}',

                                                       '{{ $room->image ? asset('storage/' . $room->image) : '' }}',
                                                       '{{ $room->class }}',
                                                       '{{ $room->status_id }}'
                                                   )">
                                                        <i class="fas fa-edit me-1"></i>แก้ไข
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger flex-grow-1"
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

    <!-- Room Modal (ใช้ได้ทั้งเพิ่มและแก้ไข) -->
    <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="roomModalLabel">เพิ่มห้องใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="roomForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="building_id" value="{{ $building->id }}">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อห้อง</label>
                            <input type="text" class="form-control shadow-sm" id="room_name" name="room_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ความจุ</label>
                            <input type="number" class="form-control shadow-sm" id="capacity" name="capacity"
                                min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ประเภทห้อง</label>
                            <select class="form-select shadow-sm" id="room_type_select" name="room_type" required>
                                <option value="">-- เลือกประเภทห้อง --</option>
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                                <option value="other">อื่น ๆ</option>
                            </select>
                            <input type="text" class="form-control shadow-sm mt-2 d-none" id="custom_room_type"
                                name="room_type_other" placeholder="ระบุประเภทห้องเอง">
                        </div>
                        <div class="form-group">
                            <label for="edit_class">ชั้นที่</label>
                            <input type="text" class="form-control" id="edit_class" name="class" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">รายละเอียดห้อง</label>
                            <textarea class="form-control shadow-sm" id="room_details" name="room_details" rows="3"></textarea>
                        </div>
                        {{-- <div class="mb-3">
                            <label class="form-label fw-bold">อัตราค่าบริการ (บาท)</label>
                            <input type="number" class="form-control shadow-sm" id="service_rates" name="service_rates"
                                min="0" required>
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">สถานะ</label>
                            <select class="form-select shadow-sm" id="status" name="status_id" required>
                                <option value="2">พร้อมใช้งาน</option>
                                <option value="1">ไม่พร้อมใช้งาน</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">รูปภาพห้อง</label>
                            <input type="file" class="form-control shadow-sm" id="image" name="image">
                            <div id="currentImage" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('room_type_select').addEventListener('change', function() {
            const customInput = document.getElementById('custom_room_type');
            if (this.value === 'other') {
                customInput.classList.remove('d-none');
                customInput.required = true;
            } else {
                customInput.classList.add('d-none');
                customInput.required = false;
            }
        });

        function openAddRoomModal() {
            document.getElementById('roomModalLabel').innerText = 'เพิ่มห้องใหม่';
            document.getElementById('submitBtn').innerText = 'บันทึก';
            document.getElementById('roomForm').action = '{{ route('manage_rooms.store') }}';
            document.getElementById('formMethod').value = 'POST';

            // Reset ค่าเดิม
            document.getElementById('room_name').value = '';
            document.getElementById('capacity').value = '';
            document.getElementById('room_type_select').value = '';
            document.getElementById('custom_room_type').value = '';
            document.getElementById('custom_room_type').classList.add('d-none');
            document.getElementById('room_details').value = '';
            // document.getElementById('service_rates').value = '';
            document.getElementById('currentImage').innerHTML = '';
            document.getElementById('status').value = '2';

            $('#roomModal').modal('show');
        }

        function openEditRoomModal(roomId, roomName, capacity, roomTypeName, roomTypeOther, roomDetails, serviceRates,
            imageUrl, roomClass, statusId) {
            document.getElementById('roomModalLabel').innerText = 'แก้ไขห้อง';
            document.getElementById('submitBtn').innerText = 'อัปเดต';
            document.getElementById('roomForm').action = `/manage_rooms/${roomId}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('edit_class').value = roomClass;
            document.getElementById('room_name').value = roomName;
            document.getElementById('capacity').value = capacity;
            document.getElementById('room_details').value = roomDetails;
            // document.getElementById('service_rates').value = serviceRates;
            document.getElementById('status').value = statusId;

            const select = document.getElementById('room_type_select');
            const customInput = document.getElementById('custom_room_type');

            if (roomTypeName === 'other') {
                select.value = 'other';
                customInput.classList.remove('d-none');
                customInput.value = roomTypeOther ?? ''; // ถ้าไม่มีค่าก็ใช้ค่าว่าง
            } else {
                select.value = roomTypeName;
                customInput.classList.add('d-none');
                customInput.value = '';
            }
            document.getElementById('currentImage').innerHTML = imageUrl ?
                `<img src="${imageUrl}" alt="Current Image" style="max-width: 100%; height: auto;" class="mt-2" />` :
                '<p class="text-muted mt-2">ไม่มีรูปภาพ</p>';

            $('#roomModal').modal('show');

            document.getElementById('roomForm').onsubmit = function(e) {
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
                                $('#roomModal').modal('hide');
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
            };

        }

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
