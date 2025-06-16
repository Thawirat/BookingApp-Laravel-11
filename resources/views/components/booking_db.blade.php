<div class="col-md-4">
    <div class="stat-card">
        <i class="fas fa-book icon"></i>
        <div class="details">
            <h3>{{ $totalBookings }}</h3>
            <p>จำนวนการจองทั้งหมด</p>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="stat-card">
        <i class="fas fa-clock icon"></i>
        <div class="details">
            <h3>{{ $pendingBookings }}</h3>
            <p>จำนวนการจองที่รอดำเนินการ</p>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="stat-card">
        <i class="fas fa-check-circle icon"></i>
        <div class="details">
            <h3>{{ $confirmedBookings }}</h3>
            <p>จำนวนการจองที่อุนมัติแล้ว</p>
        </div>
    </div>
</div>
