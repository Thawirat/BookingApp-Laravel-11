<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark" id="editUserModalLabel"><i class="fas fa-edit"></i>แก้ไขข้อมูลผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">รหัสผ่านใหม่
                            (เว้นว่างถ้าไม่ต้องการเปลี่ยน)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">บทบาท</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">ผู้ดูแลระบบหลัก</option>
                            <option value="sub-admin">ผู้ดูแลอาคาร</option>
                            <option value="user">ผู้ใช้ทั่วไป</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อาคารที่ดูแล</label>
                        <div id="buildings_container" class="border p-3 rounded">
                            <!-- Buildings will be dynamically loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
