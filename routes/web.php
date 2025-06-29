<?php

use App\Http\Controllers\Booking_dbController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingCalendarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingHistoryController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageBuildingsController;
use App\Http\Controllers\ManageRoomsController;
use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FeedbackController;

Route::get('/booking/approve/{id}', [Booking_dbController::class, 'approve'])->name('booking.approve');
Route::get('/booking/reject/{id}', [Booking_dbController::class, 'reject'])->name('booking.reject');

// Route for calendar view
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

// Public routes
Route::get('/', function () {
    return view('index');
});

// Booking routes with consistent naming
Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/{id}', [BookingController::class, 'showBookingForm'])->name('partials.booking.form');
    Route::post('/', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my', [BookingController::class, 'myBookings'])->name('booking.myBookings');
    Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Room routes
Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/type/{type}', [RoomController::class, 'byType'])->name('rooms.byType');
    Route::get('/building/{building_id}', [RoomController::class, 'byBuilding'])->name('rooms.byBuilding');
    Route::get('/popular', [RoomController::class, 'popular'])->name('rooms.popular');
});
Route::get('/', [DashboardController::class, 'showIndex'])->name('index');

Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
// ทิน
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/data', [CalendarController::class, 'getCalendarData'])->name('calendar.data');
Route::get('/calendar/table', [CalendarController::class, 'TableView'])->name('calendar.table');
// Building routes
Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{room_id}', [BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

Route::get('/bookings/{id}', [BookingController::class, 'showBookingForm'])->name('bookings.show');

Route::get('/buildings/{id}/rooms', [BuildingController::class, 'fetchRooms']);
Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index'); // Added route for buildings
Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show'); // Route for showing a specific building
Route::get('/buildings/{id}/rooms', [BuildingController::class, 'fetchRooms'])->name('buildings.fetchRooms'); // Route to fetch rooms for a specific building

// ในไฟล์ web.php
Route::get('/usage', [UsageController::class, 'index'])->name('usage.index');
Route::get('/how-to-use', function () {
    return view('how_to_use');
});
// ในไฟล์ web.php
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');


// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Admin routes
Route::middleware(['auth', 'can:admin-or-subadmin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard'); // Redirecting to the existing dashboard view
    })->name('admin.dashboard');
    Route::get('/available-rooms', [BookingController::class, 'getAvailableRooms'])->name('available.rooms');
    Route::post('/create-booking', [BookingController::class, 'store'])->name('create.booking');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Booking management
    Route::get('/booking_db', [Booking_dbController::class, 'index'])->name('booking_db');
    Route::patch('/booking/{id}/update-status', [Booking_dbController::class, 'updateStatus'])->name('booking.update-status');
    // เส้นทางห้อง
    Route::get('/booking-management', [Booking_dbController::class, 'index'])->name('booking_db');

    // เส้นทางเปลี่ยนสถานะการจอง
    Route::patch('/booking/{id}/update-status', [Booking_dbController::class, 'updateStatus'])
        ->name('booking.update-status');

    // เส้นทางการชำระ
    Route::post('/booking/{id}/confirm-payment', [Booking_dbController::class, 'confirmPayment'])
        ->name('booking.confirm-payment');

    // การจอง
    Route::get('/booking_history', [BookingHistoryController::class, 'index'])->name('booking.history');
    Route::get('/dashboard/booking_history', [App\Http\Controllers\Booking_dbController::class, 'history'])->name('booking_history');
    // Room management
    Route::get('/manage-rooms', [ManageRoomsController::class, 'index'])->name('manage_rooms.index');
    Route::get('/manage-rooms/{buildingId}/rooms', [ManageRoomsController::class, 'showRooms'])->name('manage_rooms.show');
    Route::post('/rooms', [ManageRoomsController::class, 'store'])->name('manage_rooms.store');
    Route::put('/manage_rooms/{room}', [ManageRoomsController::class, 'update'])->name('manage_rooms.update');
    Route::get('/rooms/{id}', [ManageRoomsController::class, 'showRoomDetails'])->name('manage_rooms.details');
    Route::get('/rooms/{room}/edit', [ManageRoomsController::class, 'edit'])->name('manage_rooms.edit');
    Route::delete('/manage_rooms/{room}', [ManageRoomsController::class, 'destroy'])->name('manage_rooms.destroy');
    Route::get('/booking_db', [Booking_dbController::class, 'index'])->name('booking_db');
    Route::get('/booking-history', [BookingHistoryController::class, 'index'])->name('booking_history');

    // Building management
    Route::get('/manage-buildings', [ManageBuildingsController::class, 'index'])->name('manage.buildings');
    Route::post('/manage-buildings', [ManageBuildingsController::class, 'store'])->name('manage.buildings.store');
    Route::resource('manage/buildings', ManageBuildingsController::class);
});

Route::middleware(['auth', 'can:admin-only'])->group(function () {
    Route::get('/manage-users', [ManageUsersController::class, 'index'])->name('manage_users.index');
    Route::put('/manage-users/{id}', [ManageUsersController::class, 'update'])->name('manage_users.update');
    Route::delete('/manage-users/{id}', [ManageUsersController::class, 'destroy'])->name('manage_users.destroy');
    Route::middleware('auth')->get('/api/users/{id}', [ManageUsersController::class, 'show']);
    Route::middleware('auth')->get('/api/users/{id}/buildings', [ManageUsersController::class, 'getUserBuildings']);
    Route::resource('equipments', EquipmentController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/admin/feedbacks', [FeedbackController::class, 'index'])->name('admin.feedback.index');

});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    });
    Route::post('/profile/update', [RegisterController::class, 'update'])->name('profile.update');
    Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::get('/my-bookings', [\App\Http\Controllers\BookingStatusController::class, 'index'])->name('my-bookings');
});
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
// Booking routes
Route::post('/book-room/{id}', [BookingController::class, 'bookRoom'])->name('book.room'); // Route for booking a room

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
// routes/web.php
Route::put('/user/profile', [UserController::class, 'update'])->name('user.updateProfile');
Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
Route::post('/user/update-all', [UserController::class, 'updateAll'])->name('user.updateAll');
Route::post('/payment/upload/{booking}', [BookingController::class, 'uploadSlip'])->name('booking.uploadSlip');
Route::patch('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::put('/user/update-all', [UserController::class, 'update'])->name('user.updateAll');
Route::get('/bookings/{id}/booking-pdf', [BookingController::class, 'downloadBookingPdf'])->name('mybookings.download.pdf');
Route::get('/bookings/{id}/slip-pdf', [BookingController::class, 'downloadSlipPdf'])->name('bookingslip.download.pdf');
Route::get('/my-booking-history', [BookingController::class, 'myHistory'])->name('bookings.history');
Route::get('/my-bookings/pdf', [BookingController::class, 'downloadAllHistoryPdf'])->name('bookings.download.all.pdf');
Route::get('/my-bookings/{id}/pdf', [BookingController::class, 'downloadHistoryPdf'])->name('bookings.download.pdf');
Route::resource('room-types', RoomController::class);
Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room-types');
Route::get('/room-types/create', [RoomTypeController::class, 'create'])->name('room-types.create');
Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room-types.index');
Route::resource('room-types', RoomTypeController::class)->except(['show']);
Route::patch('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
// ส่ง OTP ไปอีเมล
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// แสดงฟอร์มกรอก OTP
Route::get('verify-otp', [PasswordResetController::class, 'showOtpForm'])->name('password.otp');
// ตรวจสอบ OTP
Route::post('verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
// แสดงฟอร์มรีเซ็ตรหัสผ่าน
Route::get('reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::delete('/my-bookings/{id}/cancel', [\App\Http\Controllers\BookingStatusController::class, 'cancel'])
    ->name('mybookings.cancel');
Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
