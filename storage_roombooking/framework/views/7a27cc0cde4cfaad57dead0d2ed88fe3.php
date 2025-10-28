<!-- Modal for Booking Details -->
<div class="modal fade" id="detailsModal<?php echo e($booking->id); ?>" tabindex="-1"
    aria-labelledby="detailsModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="detailsModalLabel<?php echo e($booking->id); ?>">
                    <i class="fas fa-info-circle me-2"></i> รายละเอียดการจองห้อง
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row gy-4">
                    <!-- Booking Info -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลการจอง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>รหัสการจอง:</strong> <?php echo e($booking->ref_number); ?></li>
                            <li><strong>เรื่อง:</strong> <?php echo e($booking->title); ?></li>
                            <li><strong>สถานะการจอง:</strong> <?php echo e($booking->status->status_name); ?></li>
                            <li><strong>วันที่จอง:</strong>
                                <?php echo e(\Carbon\Carbon::parse($booking->booking_created_at)->addYear(543)->format('d/m/Y')); ?>

                            </li>
                            <li><strong>วันที่จัด-เก็บสถานที่:</strong>
                                <?php echo e(\Carbon\Carbon::parse($booking->setup_date)->addYear(543)->format('d/m/Y')); ?> -
                                <?php echo e(\Carbon\Carbon::parse($booking->teardown_date)->addYear(543)->format('d/m/Y')); ?>

                            </li>
                            <li><strong>วันที่เริ่มต้น-วันที่สิ้นสุด:</strong>
                                <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->addYear(543)->format('d/m/Y')); ?> -
                                <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->addYear(543)->format('d/m/Y')); ?></li>
                            <li><strong>เวลา:</strong>
                                <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->format('H:i')); ?>น. -
                                <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->format('H:i')); ?>น.
                            </li>
                            <li><strong>วัตถุประสงค์:</strong> <?php echo e($booking->reason ?? 'ไม่ระบุ'); ?></li>
                            <li><strong>จำนวนผู้เข้าร่วม:</strong> <?php echo e($booking->participant_count ?? 'ไม่ระบุ'); ?> คน
                            </li>
                            <li><strong>รายละเอียดกิจกรรม:</strong> <?php echo e($booking->booker_info ?? 'ไม่ระบุ'); ?></li>
                            <li>
                                <strong>อุปกรณ์ในห้อง:</strong>
                                <?php if($booking->room && $booking->room->equipments && $booking->room->equipments->count()): ?>
                                    <ul class="ps-3 mb-0">
                                        <?php $__currentLoopData = $booking->room->equipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($equipment->name); ?> <?php echo e($equipment->quantity); ?> รายการ</li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    ไม่มีอุปกรณ์
                                <?php endif; ?>
                            </li>
                            
                            
                        </ul>
                    </div>
                    <!-- Booker Info -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลผู้จอง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>ชื่อผู้จอง:</strong> <?php echo e($booking->external_name); ?></li>
                            <li><strong>อีเมล:</strong> <?php echo e($booking->external_email); ?></li>
                            <li><strong>โทรศัพท์:</strong> <?php echo e($booking->external_phone); ?></li>
                            <li><strong>ตำแหน่ง:</strong> <?php echo e($booking->external_position ?? 'ไม่ระบุ'); ?></li>
                            <li><strong>ที่อยู่/หน่วยงาน:</strong> <?php echo e($booking->external_address ?? 'ไม่ระบุ'); ?></li>
                            <li><strong>ชื่อผู้ประสาน:</strong> <?php echo e($booking->coordinator_name); ?></li>
                            <li><strong>โทรศัพท์ผู้ประสาน:</strong> <?php echo e($booking->coordinator_phone); ?></li>
                            <li><strong>ที่อยู่/หน่วยงานผู้ประสาน:</strong>
                                <?php echo e($booking->coordinator_department ?? 'ไม่ระบุ'); ?></li>
                        </ul>
                    </div>
                    <!-- Room Info -->
                    <div class="col-12">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลห้อง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>อาคาร:</strong> <?php echo e($booking->building_name); ?></li>
                            <li><strong>ห้อง:</strong> <?php echo e($booking->room_name); ?></li>
                            <?php if(!empty($booking->room) && $booking->room->room_details): ?>
                                <li><strong>ชั้น:</strong> <?php echo e($booking->room->class); ?></li>
                                <li><strong>รายละเอียด:</strong> <?php echo e($booking->room->room_details); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    ปิด
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/components/modal/history.blade.php ENDPATH**/ ?>