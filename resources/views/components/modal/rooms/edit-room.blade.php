<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editRoomModalLabel"><i class="fas fa-edit me-2"></i> แก้ไขห้อง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="building_id" value="{{ $building->id }}">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อห้อง</label>
                        <input type="text" class="form-control shadow-sm" id="edit_room_name" name="room_name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ความจุ</label>
                        <input type="number" class="form-control shadow-sm" id="edit_capacity" name="capacity"
                            min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ประเภทห้อง</label>
                        <select class="form-select shadow-sm" id="edit_room_type_select" name="room_type" required>
                            <option value="">-- เลือกประเภทห้อง --</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                            <option value="other">อื่น ๆ</option>
                        </select>
                        <input type="text" class="form-control shadow-sm mt-2 d-none" id="edit_custom_room_type"
                            name="room_type_other" placeholder="ระบุประเภทห้องเอง">
                    </div>
                    <div class="form-group">
                        <label for="edit_class">ชั้นที่</label>
                        <input type="text" class="form-control" id="edit_class" name="class" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียดห้อง</label>
                        <textarea class="form-control shadow-sm" id="edit_room_details" name="room_details" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">สถานะ</label>
                        <select class="form-select shadow-sm" id="edit_status" name="status_id" required>
                            <option value="2">พร้อมใช้งาน</option>
                            <option value="1">ไม่พร้อมใช้งาน</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รูปภาพห้อง</label>
                        <input type="file" class="form-control shadow-sm" id="edit_image" name="image">
                        <div id="currentImage" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
