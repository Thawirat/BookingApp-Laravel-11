<div class="modal fade" id="editBuildingModal" tabindex="-1" aria-labelledby="editBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editBuildingForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editBuildingModalLabel"><i class="fas fa-edit me-2"></i> แก้ไขอาคาร</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_building_name" class="form-label">ชื่ออาคาร</label>
                        <input type="text" class="form-control" id="edit_building_name" name="building_name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_citizen_save" class="form-label">ชื่อผู้บันทึก</label>
                        <input type="text" class="form-control" id="edit_citizen_save" name="citizen_save" required>
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
                    <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนเเปลง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>
