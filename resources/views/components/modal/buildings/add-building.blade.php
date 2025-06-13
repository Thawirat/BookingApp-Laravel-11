<div class="modal fade" id="addBuildingModal" tabindex="-1" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="addBuildingForm" action="{{ route('manage.buildings.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="addBuildingModalLabel">
                        <i class="fas fa-building me-2"></i>เพิ่มอาคาร
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="building_name" class="form-label">ชื่ออาคาร</label>
                        <input type="text" class="form-control rounded-3 shadow-sm" id="building_name"
                            name="building_name" placeholder="กรอกชื่ออาคาร" required>
                    </div>
                    <div class="mb-3">
                        <label for="citizen_save" class="form-label">ชื่อผู้บันทึก</label>
                        <input type="text" class="form-control rounded-3 shadow-sm" id="citizen_save"
                            name="citizen_save" placeholder="ชื่อเจ้าหน้าที่ที่บันทึก" required>
                    </div>
                    <div class="mb-3">
                        <label for="building_image" class="form-label">อัปโหลดรูปภาพอาคาร</label>
                        <input type="file" class="form-control rounded-3 shadow-sm" id="building_image"
                            name="image" accept="image/*">
                        <small class="form-text text-muted ms-1">รองรับไฟล์ jpeg, png, gif (ไม่เกิน 2MB)</small>
                        <div id="addPreviewImage" class="mt-3 text-center"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        เพิ่มอาคาร
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
