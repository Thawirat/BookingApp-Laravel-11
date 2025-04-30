
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจองห้องออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">

    <!-- ลิงก์ CSS  Bootstrap และ Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- FullCalendar และ Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/locale/th.js"></script>

    <style>
        /* Loader Overlay */
        #loading-overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s ease-in-out;
        }

        .spinner-border {
            width: 4rem;
            height: 4rem;
        }

        /* Fixed Sidebar Layout */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background-color: #fff;
            padding: 25px 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            z-index: 100;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .content {
            margin-left: 220px;
            padding: 25px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -220px;
                transition: left 0.3s;
            }
            .sidebar.sidebar-open {
                left: 0;
            }
            .content {
                margin-left: 0;
                padding: 15px;
            }
            #toggleSidebar {
                display: block;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 200;
            }
        }
        @media (min-width: 992px) {
            #toggleSidebar {
                display: none;
            }
        }
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f5f5f7;
            color: #333;
            background-image: url('{{ asset('images/bg-1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        /* ... (คง style อื่นๆ เดิมไว้) ... */
    </style>
    @stack('styles')
</head>

<body>
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <button class="btn btn-outline-secondary d-md-none my-2" id="toggleSidebar">
        <i class="fas fa-bars"></i> เมนู
    </button>
    <div class="sidebar " id="sidebar">
        <img src="{{ asset('images/snru-logo.jpeg') }}" alt="SNru Logo" style="width: 100%; height: auto;">
        <!-- เมนูแนวตั้ง -->
        <nav>
            <ul class="nav flex-column">
                {{-- เมนูทั่วไป --}}
                <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link text-gray-700">
                        <i class="fas fa-home me-2"></i> หน้าแรก
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('calendar.index') }}" class="nav-link text-gray-700">
                        <i class="fas fa-calendar-alt me-2"></i> ปฏิทินการจอง
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('booking.index') }}" class="nav-link text-gray-700">
                        <i class="fas fa-door-open me-2"></i> จองห้อง
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('usage.index') }}" class="nav-link text-gray-700">
                        <i class="fas fa-info-circle me-2"></i> วิธีใช้งาน
                    </a>
                </li>
                {{-- เฉพาะผู้ดูแลระบบหรือผู้ดูแลอาคาร --}}
                @if (Auth::check() && Auth::user()->isAdminOrSubAdmin())
                    <hr>
                    <h6 class="sidebar-heading text-white text-center py-2 px-3 mb-3"
                        style="background-color: #343a40; border-radius: 0.25rem;">สำหรับผู้ดูแล</h6>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link text-gray-700">
                            <i class="fas fa-tachometer-alt me-2"></i> แดชบอร์ด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('booking_db') }}" class="nav-link text-gray-700">
                            <i class="fas fa-calendar-check me-2"></i> การจองห้อง
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage_rooms.index') }}" class="nav-link text-gray-700">
                            <i class="fas fa-building me-2"></i> จัดการห้องและอาคาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('booking_history') }}" class="nav-link text-gray-700">
                            <i class="fas fa-history me-2"></i> ประวัติการจองห้อง
                        </a>
                    </li>
                    @if (Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a href="{{ route('manage_users.index') }}" class="nav-link text-gray-700">
                                <i class="fas fa-users-cog me-2"></i> จัดการผู้ใช้
                            </a>
                        </li>
                    @endif
                @endif
                {{-- เมนูล็อกอิน/ล็อกเอาท์ --}}
                @if (Auth::check())
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link text-danger border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link text-gray-700">
                            <i class="fas fa-sign-in-alt me-2"></i> เข้าสู่ระบบ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link text-gray-700">
                            <i class="fas fa-user-plus me-2"></i> สมัครสมาชิก
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    <div class="content">
        @yield('content')
        @yield('scripts')
    </div>
    @include('footer')
    <script>
        // Sidebar toggle for mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('sidebar-open');
        });
        moment.locale('th');
        // Loader overlay logic
        $(document).ready(function() {
            $("a").on("click", function(event) {
                let target = $(this).attr("target");
                if (!target || target === "_self") {
                    $("#loading-overlay").css({
                        "visibility": "visible",
                        "opacity": "1"
                    });
                }
            });
            $(window).on("load", function() {
                $("#loading-overlay").css({
                    "visibility": "hidden",
                    "opacity": "0"
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>


<style>
    /* Main Layout Styles */
    body {
        font-family: 'Kanit', sans-serif;
        background-color: #f5f5f7;
        color: #333;
        background-image: url('{{ asset('images/bg-1.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .container-fluid {
        padding: 0;
    }

    /* Sidebar Styles */
    .sidebar {
        background-color: #fff;
        height: 100vh;
        padding: 25px 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);

        top: 0;
        z-index: 100;
        transition: all 0.3s ease;
    }

    .fixed-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        /* ให้สูงเท่าหน้าจอ */
        overflow-y: auto;
        /* ถ้าด้านในยาวให้เลื่อนเฉพาะในนี้ */
        background-color: #f8f9fa;
        /* หรือสีที่คุณใช้ */
        width: 16.6666667%;
        /* เท่ากับ col-md-2 (2/12) */
        z-index: 1000;
        /* ให้ลอยอยู่เหนือเนื้อหาอื่น */
    }

    .sidebar h4 {
        color: #333;
        font-weight: 700;
        padding: 10px 0 20px 10px;
        margin-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
        font-size: clamp(18px, 4vw, 22px);
    }

    .sidebar .nav-link {
        color: #666;
        font-weight: 500;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-size: clamp(14px, 3vw, 16px);
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
        font-size: clamp(16px, 3.5vw, 18px);
    }

    .sidebar .nav-link:hover {
        background-color: #fff9e6;
        color: #FFC107;
    }

    .sidebar .nav-link.active {
        background-color: #FFC107;
        color: #fff;
    }

    .sidebar form {
        margin-top: 20px;
    }

    .sidebar form button.nav-link {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        color: #F44336;
    }

    .sidebar form button.nav-link:hover {
        background-color: rgba(244, 67, 54, 0.1);
        color: #F44336;
    }

    /* Content Area */
    .content {
        padding: 25px;
        min-height: 100vh;
    }

    /* Header Styles */
    h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        font-size: clamp(20px, 5vw, 28px);
    }



    /* Search Section */
    .d-flex.align-items-center {
        display: flex;
        align-items: center;
        height: 70px;
        flex-wrap: wrap;
    }

    /* Search Bar */
    .search-bar {
        border: none;
        background-color: #f5f5f7;
        border-radius: 30px;
        padding: 10px 15px;
        width: 200px;
        margin-right: 0;
        height: 40px;
    }

    form.d-flex {
        position: relative;
        margin-right: 15px;
    }

    form.d-flex button.icon-btn {
        position: absolute;
        right: 0;
        background: transparent;
        box-shadow: none;
    }

    .icon-btn {
        background-color: #f5f5f7;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 15px;
        object-fit: cover;
    }

    /* Stat Cards */
    .stat-card {
        background-color: #fff;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        height: 100px;
        margin-bottom: 25px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card .icon {
        font-size: 24px;
        background-color: #FFC107;
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        margin-right: 15px;
    }

    .stat-card .details h3 {
        font-size: clamp(18px, 4vw, 24px);
        font-weight: 700;
        margin: 0;
        color: #333;
    }

    .stat-card .details p {
        margin: 5px 0 0;
        color: #777;
        font-size: clamp(12px, 2.5vw, 14px);
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }

    .card-header h5 {
        font-weight: 600;
        margin: 0;
        color: #333;
        font-size: clamp(16px, 3.5vw, 18px);
    }

    .card-body {
        padding: 0;
        overflow-x: auto;
    }

    /* Table Styles - Enhanced */
    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        font-weight: 600;
        color: #555;
        border: none;
        padding: 15px 12px;
        background-color: #f9f9f9;
        position: sticky;
        top: 0;
        z-index: 10;
        text-align: left;
        font-size: clamp(13px, 2.8vw, 15px);
    }

    .table td {
        padding: 15px 12px;
        vertical-align: middle;
        border: none;
        border-top: 1px solid #eee;
        font-size: clamp(12px, 2.5vw, 14px);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-striped tbody tr:hover {
        background-color: #f0f0f0;
        transition: background-color 0.3s ease;
    }

    /* Colorful Status Indicators */
    td:nth-child(8) {
        font-weight: 600;
    }

    td:nth-child(8) {
        position: relative;
        padding-left: 20px !important;
    }

    td:nth-child(8)::before {
        content: "";
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    td:nth-child(8)[data-status="approved"],
    td:nth-child(8):contains("approved") {
        color: #4CAF50;
    }

    td:nth-child(8)[data-status="approved"]::before,
    td:nth-child(8):contains("approved")::before {
        background-color: #4CAF50;
    }

    td:nth-child(8)[data-status="pending"],
    td:nth-child(8):contains("pending") {
        color: #FFC107;
    }

    td:nth-child(8)[data-status="pending"]::before,
    td:nth-child(8):contains("pending")::before {
        background-color: #FFC107;
    }

    td:nth-child(8)[data-status="rejected"],
    td:nth-child(8):contains("rejected") {
        color: #F44336;
    }

    td:nth-child(8)[data-status="rejected"]::before,
    td:nth-child(8):contains("rejected")::before {
        background-color: #F44336;
    }

    /* Payment Status Styling */
    td:nth-child(9) {
        font-weight: 600;
        /*border-radius: 20px;*/
        padding: 5px 12px !important;
        text-align: center;
    }

    td:nth-child(9)[data-payment="paid"],
    td:nth-child(9):contains("paid") {
        color: #fff;
        background-color: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }

    td:nth-child(9)[data-payment="unpaid"],
    td:nth-child(9):contains("unpaid") {
        background-color: rgba(244, 67, 54, 0.2);
        color: #F44336;
    }

    td:nth-child(9)[data-payment="partial"],
    td:nth-child(9):contains("partial") {
        background-color: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    /* Button Styles */
    .btn {
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.3s ease;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .btn-success {
        background-color: #FFC107;
        border-color: #FFC107;
        color: #333;
    }

    .btn-success:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: #333;
    }

    .btn-danger {
        background-color: #F44336;
        border-color: #F44336;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    /* Mobile responsiveness */
    @media (max-width: 1200px) {
        .stat-card {
            height: auto;
            min-height: 100px;
        }

        .stat-card .icon {
            font-size: 20px;
            padding: 12px;
        }
    }

    @media (max-width: 992px) {
        .content {
            padding: 20px 15px;
        }

        .sidebar {
            padding: 20px 15px;
        }

        .search-bar {
            width: 150px;
        }
    }

    @media (max-width: 768px) {
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .d-flex.align-items-center {
            margin-top: 15px;
            width: 100%;
            justify-content: space-between;
        }

        .search-bar {
            width: 100%;
        }

        .sidebar {
            height: auto;
            position: relative;
            padding: 15px 10px;
        }

        .card-header,
        .card-body {
            padding: 10px;
        }

        .table th,
        .table td {
            padding: 10px 8px;
        }

        .stat-card {
            margin-bottom: 15px;
        }

        .icon-btn {
            width: 35px;
            height: 35px;
        }

        .profile-img {
            width: 35px;
            height: 35px;
        }
    }

    @media (max-width: 576px) {
        .content {
            padding: 15px 10px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-card .icon {
            padding: 10px;
            font-size: 18px;
            margin-right: 10px;
        }

        .table-responsive {
            border: none;
        }

        /* Adjust table for very small screens */
        .table th,
        .table td {
            padding: 8px 5px;
            white-space: nowrap;
        }

        /* Make buttons stack on very small screens */
        td:last-child {
            display: flex;
            flex-direction: column;
        }

        td:last-child .btn {
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
        }

        /* Collapse sidebar on mobile */
        .sidebar-collapse .sidebar {
            transform: translateX(-100%);
        }

        /* Mobile menu toggle button */
        .mobile-menu-toggle {
            display: block;
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background-color: #FFC107;
            color: #333;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
        }
    }

    /* Add mobile menu toggle visibility */
    .mobile-menu-toggle {
        display: none;
    }

    @media (max-width: 576px) {
        .mobile-menu-toggle {
            display: block;
        }
    }

    /* For extra small devices */
    @media (max-width: 375px) {
        .d-flex.align-items-center {
            flex-direction: column;
            align-items: flex-start !important;
            height: auto;
        }

        .profile-img,
        .icon-btn {
            margin-top: 10px;
            margin-left: 0;
        }

        form.d-flex {
            width: 100%;
            margin-right: 0;
        }

        /* Adjust status indicators for small screens */
        td:nth-child(8),
        td:nth-child(9) {
            min-width: 80px;
        }
    }
</style>
