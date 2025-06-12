<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('equipments.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addEquipmentModalLabel"><i class="fas fa-plus-circle me-2"></i>เพิ่มวัสดุอุปกรณ์</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">ชื่ออุปกรณ์ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">รายละเอียด</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">จำนวนทั้งหมด <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="remaining" class="form-label">จำนวนคงเหลือ</label>
                            <input type="number" class="form-control" id="remaining" name="remaining" min="0">
                            <div class="form-text">ถ้าไม่กรอก จะเท่ากับจำนวนทั้งหมด</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">เพิ่มอุปกรณ์</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
