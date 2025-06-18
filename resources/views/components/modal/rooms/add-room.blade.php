<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addRoomModalLabel"><i class="fas fa-building me-2"></i> เพิ่มห้องใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoomForm" method="POST" action="{{ route('manage_rooms.store') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="building_id" value="{{ $building->id }}">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อห้อง</label>
                        <input type="text" class="form-control shadow-sm" id="add_room_name" name="room_name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ความจุ</label>
                        <input type="number" class="form-control shadow-sm" id="add_capacity" name="capacity"
                            min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ประเภทห้อง</label>
                        <select class="form-select shadow-sm" id="add_room_type_select" name="room_type" required>
                            <option value="">-- เลือกประเภทห้อง --</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                            <option value="other">อื่น ๆ</option>
                        </select>
                        <input type="text" class="form-control shadow-sm mt-2 d-none" id="add_custom_room_type"
                            name="room_type_other" placeholder="ระบุประเภทห้องเอง">
                    </div>
                    <div class="mb-3">
                        <label for="add_class" class="form-label fw-bold">ชั้นที่</label>
                        <input type="text" class="form-control" id="add_class" name="class" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียดห้อง</label>
                        <textarea class="form-control shadow-sm" id="add_room_details" name="room_details" rows="3"></textarea>
                    </div>
                    <div id="equipment-wrapper" class="mb-3">
                        <label for="add_equipment" class="form-label fw-bold">อุปกรณ์ภายในห้อง</label>
                        <div class="row g-2 mb-2 equipment-row">
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
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-equipment"><i
                                        class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" id="add-equipment-btn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus me-1"></i> เพิ่มอุปกรณ์
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">สถานะ</label>
                        <select class="form-select shadow-sm" id="add_status" name="status_id" required>
                            <option value="2">พร้อมใช้งาน</option>
                            <option value="1">ไม่พร้อมใช้งาน</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รูปภาพห้อง</label>
                        <input type="file" class="form-control shadow-sm" id="add_image" name="image">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">เพิ่มห้องใหม่</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
