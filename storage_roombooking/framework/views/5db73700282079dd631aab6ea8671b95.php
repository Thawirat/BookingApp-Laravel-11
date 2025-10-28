<?php if($paginator->hasPages()): ?>
    <nav class="d-flex flex-column align-items-center mt-4">

        
        <div class="mb-2 text-center">
            <p class="small text-muted mb-0">
                กำลังแสดง
                <span class="fw-semibold"><?php echo e($paginator->firstItem()); ?></span>
                ถึง
                <span class="fw-semibold"><?php echo e($paginator->lastItem()); ?></span>
                จาก
                <span class="fw-semibold"><?php echo e($paginator->total()); ?></span>
                รายการ
            </p>
        </div>

        
        <div class="d-flex justify-content-center">
            <ul class="pagination mb-0">
                
                <?php if($paginator->onFirstPage()): ?>
                    <li class="page-item disabled" aria-disabled="true" aria-label="ก่อนหน้า">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="ก่อนหน้า">&lsaquo;</a>
                    </li>
                <?php endif; ?>

                
                <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if(is_string($element)): ?>
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                    <?php endif; ?>

                    
                    <?php if(is_array($element)): ?>
                        <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $paginator->currentPage()): ?>
                                <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                            <?php else: ?>
                                <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($paginator->hasMorePages()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="ถัดไป">&rsaquo;</a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled" aria-disabled="true" aria-label="ถัดไป">
                        <span class="page-link" aria-hidden="true">&rsaquo;</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/vendor/pagination/bootstrap-5.blade.php ENDPATH**/ ?>