<!-- แถวแรก: 4 การ์ด -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <i class="fas fa-users icon"></i>
            <div class="details">
                <h3>{{ $totalUsers }}</h3>
                <p>จำนวนผู้ใช้ทั้งหมด</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <i class="fas fa-user-shield icon"></i>
            <div class="details">
                <h3>{{ $adminCount }}</h3>
                <p>จำนวนผู้ใช้ระบบ</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <i class="fas fa-building-shield icon"></i>
            <div class="details">
                <h3>{{ $subAdminCount }}</h3>
                <p>ผู้ดูแลอาคาร</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <i class="fas fa-user icon"></i>
            <div class="details">
                <h3>{{ $regularUserCount }}</h3>
                <p>จำนวนผู้ใช้ทั่วไป</p>
            </div>
        </div>
    </div>
</div>

<!-- แถวที่สอง: 3 การ์ด -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <i class="fas fa-hourglass-half icon"></i>
            <div class="details">
                <h3>{{ $statusPendingCount }}</h3>
                <p>รออนุมัติ</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <i class="fas fa-check-circle icon"></i>
            <div class="details">
                <h3>{{ $statusActiveCount }}</h3>
                <p>อนุมัติแล้ว</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <i class="fas fa-times-circle icon"></i>
            <div class="details">
                <h3>{{ $statusRejectedCount }}</h3>
                <p>ไม่อนุมัติ</p>
            </div>
        </div>
    </div>
</div>
