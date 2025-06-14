<footer class="bg-dark text-white py-5 mt-5 border-top border-secondary">
    <div class="container">
        <div class="row gy-4 justify-content-center text-center text-md-start">

            <!-- ลิงก์สำคัญ -->
            <div class="col-md-4 col-lg-3">
                <h5 class="fw-bold mb-3">ลิงก์สำคัญ</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none link-hover">
                            <i class="fas fa-file-alt me-2"></i> นโยบายความเป็นส่วนตัว
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none link-hover">
                            <i class="fas fa-gavel me-2"></i> ข้อตกลงการใช้งาน
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white text-decoration-none link-hover">
                            <i class="fas fa-headset me-2"></i> ติดต่อฝ่ายสนับสนุน
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ข้อมูลติดต่อ -->
            <div class="col-md-4 col-lg-3">
                <h5 class="fw-bold mb-3">ข้อมูลติดต่อ</h5>
                <p class="mb-2">
                    <i class="fas fa-envelope me-2"></i> itcenter@snru.ac.th
                </p>
                <p class="mb-0">
                    <i class="fas fa-phone-alt me-2"></i> 042-970-000 ต่อ 1234
                </p>
            </div>

            <!-- โซเชียลมีเดีย -->
            <div class="col-md-4 col-lg-3">
                <h5 class="fw-bold mb-3">ติดตามเรา</h5>
                <div class="d-flex justify-content-center justify-content-md-start gap-3 fs-4">
                    <a href="#" class="text-white link-hover"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white link-hover"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white link-hover"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- เส้นคั่น -->
        <hr class="border-secondary my-4">

        <!-- ลิขสิทธิ์ -->
        <div class="text-center small text-secondary">
            &copy; {{ date('Y') }} มหาวิทยาลัยราชภัฏสกลนคร. สงวนลิขสิทธิ์.
        </div>
    </div>
</footer>

<!-- Hover Style -->
<style>
    .link-hover:hover {
        color: #0d6efd !important;
        transition: color 0.3s ease;
    }

    footer i {
        width: 20px;
        text-align: center;
    }
</style>
