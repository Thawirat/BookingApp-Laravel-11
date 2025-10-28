<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">รายการวัสดุ/อุปกรณ์</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                <i class="fas fa-plus"></i> เพิ่มอุปกรณ์
            </button>
            <?php echo $__env->make('components.modal.equipments.add-equipments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <form method="GET" action="<?php echo e(route('equipments.index')); ?>" class="row g-2 mb-3 ">
            <div class="col-md-4">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control"
                    placeholder="ค้นหาด้วยชื่อหรือคำอธิบาย">
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="">-- เรียงตามจำนวน --</option>
                    <option value="asc" <?php echo e(request('sort') == 'asc' ? 'selected' : ''); ?>>น้อย → มาก</option>
                    <option value="desc" <?php echo e(request('sort') == 'desc' ? 'selected' : ''); ?>>มาก → น้อย</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> ค้นหา / กรอง</button>
                <a href="<?php echo e(route('equipments.index')); ?>" class="btn btn-danger">รีเซ็ต</a>
            </div>
        </form>
        <!-- ตารางรายการอุปกรณ์ -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ลำดับที่</th>
                                <th class="text-center">ชื่อ</th>
                                <th class="text-center">รายละเอียด</th>
                                <th class="text-center">จำนวนทั้งหมด</th>
                                <th class="text-center">คงเหลือ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $equipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center"><?php echo e($equipment->name); ?></td>
                                    <td class="text-center"><?php echo e($equipment->description ?? '-'); ?></td>
                                    <td class="text-center"><?php echo e($equipment->quantity); ?></td>
                                    <td class="text-center"><?php echo e($equipment->remaining); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editEquipmentModal-<?php echo e($equipment->id); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDeleteEquipment(<?php echo e($equipment->id); ?>, '<?php echo e($equipment->name); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <!-- Hidden form for delete -->
                                        <form id="deleteEquipmentForm<?php echo e($equipment->id); ?>"
                                            action="<?php echo e(route('equipments.destroy', $equipment->id)); ?>" method="POST"
                                            class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                        <?php echo $__env->make('components.modal.equipments.edit-equipments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">ยังไม่มีอุปกรณ์ในระบบ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($equipments->appends(request()->query())->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<script>
    function confirmDeleteEquipment(id, name) {
        Swal.fire({
            title: 'ลบอุปกรณ์?',
            html: `คุณแน่ใจหรือไม่ว่าต้องการลบ <strong>${name}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteEquipmentForm${id}`).submit();
            }
        });
    }
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/equipments.blade.php ENDPATH**/ ?>