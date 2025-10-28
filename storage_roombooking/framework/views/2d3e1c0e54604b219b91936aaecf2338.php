<?php $__env->startSection('content'); ?>
    <div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>ประวัติการจองห้อง</h2>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-book icon"></i>
                        <div class="details">
                            <h3><?php echo e($totalBookings); ?></h3>
                            <p>จำนวนการจองทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-check-circle icon"></i>
                        <div class="details">
                            <h3><?php echo e($completedBookings); ?></h3>
                            <p>การจองที่เสร็จสิ้น</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <i class="fas fa-times-circle icon"></i>
                        <div class="details">
                            <h3><?php echo e($cancelledBookings); ?></h3>
                            <p>การจองที่ยกเลิก</p>
                        </div>
                    </div>
                </div>
            </div>
            <form action="<?php echo e(route('booking_history')); ?>" method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="ชื่อ/อีเมล/รหัสการจอง...">
                </div>
                <div class="btn-group col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>
                    <a href="<?php echo e(route('booking_history')); ?>" class="btn btn-secondary">ล้างการค้นหา</a>
                </div>
                <div class="col-md-2">
                    <select name="status_id" class="form-select" onchange="this.form.submit()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="6" <?php echo e(request('status_id') == '6' ? 'selected' : ''); ?>>เสร็จสิ้นการจอง</option>
                        <option value="5" <?php echo e(request('status_id') == '5' ? 'selected' : ''); ?>>ยกเลิกการจอง</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="desc" <?php echo e(request('sort') == 'desc' ? 'selected' : ''); ?>>เรียงล่าสุด</option>
                        <option value="asc" <?php echo e(request('sort') == 'asc' ? 'selected' : ''); ?>>เรียงเก่าสุด</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="booking_date" value="<?php echo e(request('booking_date')); ?>" class="form-control"
                        onchange="this.form.submit()">
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-history me-2"></i> รายการประวัติการจอง
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">ลำดับที่</th>
                                            <th class="text-center">รหัสการจอง</th>
                                            <th class="text-center">ห้องที่จอง</th>
                                            <th class="text-center">ผู้จองห้อง</th>
                                            <th class="text-center">เบอร์โทรศัพท์</th>
                                            <th class="text-center">วันที่จอง</th>
                                            <th class="text-center">วันที่เริ่มต้น-สิ้นสุดการจอง</th>
                                            
                                            <th class="text-center">สถานะการอนุมัติ</th>
                                            <th class="text-center">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo e(($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration); ?>

                                                </td>
                                                <td class="text-center"><span
                                                        class="badge bg-light text-dark"><?php echo e($booking->ref_number); ?></span>
                                                </td>
                                                <td class="text-center"><span class="fw-bold"><?php echo e($booking->room_name); ?></span></td>
                                                <td class="text-center">
                                                    <div class="fw-bold"><?php echo e($booking->external_name); ?></div>
                                                    <small class="text-muted"><?php echo e($booking->external_email); ?></small>
                                                </td>
                                                <td class="text-center"><?php echo e($booking->external_phone); ?></td>
                                                <td class="text-center">
                                                    <div><i class="far fa-calendar-alt me-1"></i>
                                                        <?php echo e(\Carbon\Carbon::parse($booking->created_at)->addYear(543)->format('d/m/Y')); ?>

                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->addYear(543)->format('d/m/Y')); ?>

                                                        -
                                                        <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->addYear(543)->format('d/m/Y')); ?>

                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->format('H:i')); ?>น.
                                                        -
                                                        <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->format('H:i')); ?>น.
                                                    </small>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <span
                                                        class="badge
                                                <?php if($booking->status_id == 5): ?> bg-danger
                                                <?php elseif($booking->status_id == 6): ?> bg-primary
                                                <?php elseif($booking->status_id == 8): ?> bg-danger
                                                <?php else: ?> bg-secondary <?php endif; ?>">
                                                        <?php echo e($booking->status->status_name); ?>

                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="#"
                                                            class="btn btn-outline-primary btn-sm view-details"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailsModal<?php echo e($booking->id); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php echo $__env->make('components.modal.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                                    <p>ไม่พบข้อมูลประวัติการจอง</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($bookings->appends(['search' => request('search'), 'booking_date' => request('booking_date')])->links('pagination::bootstrap-5')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var datepicker = document.getElementById("datepicker");

        var calendar = flatpickr(datepicker, {
            dateFormat: "Y-m-d",
            defaultDate: "<?php echo e(request('booking_date')); ?>",
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    window.location.href =
                        `<?php echo e(route('booking_history')); ?>?booking_date=${dateStr}`;
                }
            }
        });

        document.getElementById("openCalendar").addEventListener("click", function() {
            calendar.open(); // เปิด Flatpickr ทันทีเมื่อกดปุ่ม
        });
    });
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/booking_history.blade.php ENDPATH**/ ?>