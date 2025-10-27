<div class="card me-3 flex-shrink-0" style="width: 300px;">
    <div class="position-relative">
        <img src="<?php echo e($room->image ? asset('storage/' . $room->image) : asset('images/no-picture.jpg')); ?>"
            class="card-img-top"
            alt="รูปภาพห้อง <?php echo e($room->room_name); ?>"
            style="height: 200px; object-fit: cover;">

        <!-- สถานะซ้อนบนภาพ -->
        <span class="position-absolute top-0 end-0 m-2 badge
            bg-<?php echo e($room->status->status_name === 'พร้อมใช้งาน' ? 'success' : 'danger'); ?>">
            <?php echo e($room->status->status_name); ?>

        </span>
    </div>

    <div class="card-body d-flex flex-column justify-content-between">
        <div class="ps-3 pe-3 pt-3 pb-3">
            <h5 class="fw-bold text-dark"><?php echo e($room->room_name); ?></h5>
            <p class="text-muted mb-1">
                <i class="fas fa-building me-1"></i> อาคาร <?php echo e($room->building->building_name); ?>

                ชั้น <?php echo e($room->class); ?>

            </p>
            <p class="text-muted mb-1">
                <i class="fas fa-users me-1"></i> <?php echo e($room->capacity); ?> คน
            </p>

            <?php if($room->status->status_name === 'พร้อมใช้งาน'): ?>
                <a href="<?php echo e(route('partials.booking.form', ['id' => $room->room_id])); ?>"
                    class="btn btn-warning w-100">
                    จองห้องนี้
                </a>
            <?php else: ?>
                <button class="btn btn-secondary w-100" disabled>ไม่พร้อมให้จอง</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php /**PATH /var/www/html/resources/views/components/room-card.blade.php ENDPATH**/ ?>