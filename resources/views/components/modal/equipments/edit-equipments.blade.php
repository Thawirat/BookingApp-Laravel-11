<div class="modal fade" id="editEquipmentModal-{{ $equipment->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $equipment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('equipments.update', $equipment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editModalLabel{{ $equipment->id }}"><i class="fas fa-edit me-2"></i>แก้ไขอุปกรณ์</h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name-{{ $equipment->id }}" class="form-label">ชื่ออุปกรณ์</label>
                        <input type="text" name="name" id="name-{{ $equipment->id }}" class="form-control" value="{{ $equipment->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description-{{ $equipment->id }}" class="form-label">รายละเอียด</label>
                        <textarea name="description" id="description-{{ $equipment->id }}" class="form-control">{{ $equipment->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="quantity-{{ $equipment->id }}" class="form-label">จำนวน</label>
                        <input type="number" name="quantity" id="quantity-{{ $equipment->id }}" class="form-control" value="{{ $equipment->quantity }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="available-{{ $equipment->id }}" class="form-label">คงเหลือ</label>
                        <input type="number" name="remaining" class="form-control" value="{{ $equipment->remaining }}" min="0" max="{{ $equipment->quantity }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>
</div>
