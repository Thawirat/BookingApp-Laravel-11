<footer class="bg-dark text-white py-3 mt-3 border-top border-secondary">
    <div class="container">
        <div class="row gy-3 justify-content-center text-center text-md-start">

            <!-- ลิงก์สำคัญ -->
            <div class="col-md-4 col-lg-3">
                <h6 class="fw-bold mb-2 text-uppercase">ลิงก์สำคัญ</h6>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="#" class="text-white-50 text-decoration-none link-hover">
                            <i class="fas fa-file-alt me-2"></i> นโยบายความเป็นส่วนตัว
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 text-decoration-none link-hover">
                            <i class="fas fa-gavel me-2"></i> ข้อตกลงการใช้งาน
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 text-decoration-none link-hover">
                            <i class="fas fa-headset me-2"></i> ติดต่อฝ่ายสนับสนุน
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ข้อมูลติดต่อ -->
            <div class="col-md-4 col-lg-4">
                <h6 class="fw-bold mb-2 text-uppercase">ข้อมูลติดต่อ</h6>
                <p class="mb-1 small text-white-50">
                    มหาวิทยาลัยราชภัฏสกลนคร<br>
                    680 ถนนนิตโย ตำบลธาตุเชิงชุม อำเภอเมือง จังหวัดสกลนคร 47000
                </p>
                <p class="mb-0 small text-white-50">
                    โทรศัพท์ 042-970021 , 042-970094 | โทรสาร 042-970022
                </p>
            </div>

            <!-- โซเชียลมีเดีย -->
            <div class="col-md-4 col-lg-3">
                <h6 class="fw-bold mb-2 text-uppercase">ติดตามเรา</h6>
                <div class="d-flex justify-content-center justify-content-md-start gap-3 fs-5">
                    <a href="https://www.facebook.com/SNRUOfficial" target="_blank" class="text-white link-hover"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@SNRU" target="_blank" class="text-white link-hover"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.snru.ac.th/th" target="_blank" class="text-white link-hover"><i class="fas fa-globe"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-3">

        <div class="text-center small text-secondary">
            &copy; <?php echo e(date('Y')); ?> มหาวิทยาลัยราชภัฏสกลนคร. สงวนลิขสิทธิ์.
        </div>
    </div>
</footer>

<style>
    .link-hover:hover {
        color: #0d6efd !important;
        transition: color 0.3s ease;
    }
    footer i {
        width: 18px;
        text-align: center;
    }
</style>
<?php /**PATH /var/www/html/resources/views/footer.blade.php ENDPATH**/ ?>