<footer class="bg-dark text-white py-5 mt-5 border-top border-secondary text-center">
    <div class="container-fluid">
        <div class="row justify-content-center gy-4">
            <!-- ลิงก์สำคัญ -->
            <div class="col-md-3">
                <h5 class="fw-bold mb-3">ลิงก์สำคัญ</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a class="text-white text-decoration-none link-hover" href="#">นโยบายความเป็นส่วนตัว</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none link-hover" href="#">ข้อตกลงการใช้งาน</a></li>
                    <li><a class="text-white text-decoration-none link-hover" href="#">ติดต่อฝ่ายสนับสนุน</a></li>
                </ul>
            </div>

            <!-- ข้อมูลติดต่อ -->
            <div class="col-md-3">
                <h5 class="fw-bold mb-3">ข้อมูลติดต่อ</h5>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i> support@university.com</p>
                <p><i class="fas fa-phone-alt me-2"></i> 02-123-4567</p>
            </div>

            <!-- โซเชียลมีเดีย -->
            <div class="col-md-3">
                <h5 class="fw-bold mb-3">ติดตามเรา</h5>
                <div class="d-flex justify-content-center gap-3 fs-4">
                    <a class="text-white link-hover" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="text-white link-hover" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="text-white link-hover" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-5 small text-secondary">
            &copy; {{ date('Y') }} มหาวิทยาลัยราชภัฏสกลนคร. สงวนลิขสิทธิ์.
        </div>
    </div>
</footer>

<style>
    .link-hover:hover {
        color: #0d6efd !important;
        transition: color 0.3s ease;
    }
</style>

