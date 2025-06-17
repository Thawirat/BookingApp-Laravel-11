@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>ภาพรวม</h2>
        </div>
        <div class="row">
            @php
                $stats = [
                    ['icon' => 'fa-building', 'label' => 'จำนวนอาคาร', 'value' => $totalBuildings ?? 0],
                    ['icon' => 'fa-door-closed', 'label' => 'จำนวนห้อง', 'value' => $totalRooms ?? 0],
                    ['icon' => 'fa-users', 'label' => 'จำนวนผู้ใช้', 'value' => $totalUsers ?? 0],
                    [
                        'icon' => 'fa-calendar-check',
                        'label' => 'จำนวนการจองห้อง',
                        'value' => $totalBookings ?? 0,
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card">
                        <div class="icon"><i class="fas {{ $stat['icon'] }}"></i></div>
                        <div class="details">
                            <h3>{{ $stat['value'] }}</h3>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <!-- การจองล่าสุด -->
            <div class="col-md-8">
                <div class="p-3 mb-4 bg-white rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>การจองล่าสุด</h5>
                        <a href="{{ route('booking_db') }}" class="custom-link">ดูทั้งหมด</a>
                    </div>

                    <div class="booking-carousel" id="bookingCarousel">
                        @forelse ($recentBookings as $booking)
                            <div class="booking-card">
                                @if (!empty($booking->room) && !empty($booking->room->image))
                                    <img src="{{ asset('storage/' . $booking->room->image) }}"
                                        alt="{{ $booking->room->room_name ?? 'Room Image' }}"
                                        class="img-fluid rounded-top w-100" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-top d-flex align-items-center justify-content-center"
                                        style="height: 180px;">
                                        <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                    </div>
                                @endif
                                <p class="room-name">{{ $booking->room_name }}</p>
                                <p class="building-name">{{ $booking->building_name }}</p>
                                <p class="booker-name">{{ $booking->booker_name }}</p>
                                <button type="button" class="btn btn-outline btn-sm flex-grow-1" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal{{ $booking->id }}">
                                    <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                </button>
                            </div>
                            @include('booking-status.modal')
                        @empty
                            <p>ไม่มีการจองล่าสุด</p>
                        @endforelse
                    </div>
                    <div class="carousel-controls mt-2">
                        <button class="icon-btn" onclick="scrollCarousel(-200)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="icon-btn" onclick="scrollCarousel(200)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded shadow-sm">
                    <h5>สถิติการจองรายสัปดาห์</h5>
                    <div class="chart-container">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const data = @json($weeklyStats ?? []);
        if (data.length) {
            const ctx = document.getElementById("bookingChart").getContext("2d");
            const weeks = data.map(item => "สัปดาห์ " + item.week);
            const totals = data.map(item => item.total);

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: weeks,
                    datasets: [{
                        label: "จำนวนการจอง (รายสัปดาห์)",
                        data: totals,
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: "rgba(54, 162, 235, 1)",
                        pointRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });

    function scrollCarousel(amount) {
        document.getElementById('bookingCarousel').scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }

    function showDetails(room, booker, date, time) {
        alert(`ห้อง: ${room}\nผู้จอง: ${booker}\nวันที่จอง: ${date}\nเวลา: ${time}`);
    }
</script>

<style>
    .booking-carousel {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 20px;
        padding-bottom: 10px;
    }

    .booking-carousel::-webkit-scrollbar {
        display: none;
    }

    .booking-card {
        min-width: 160px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.2s;
    }

    .booking-card:hover {
        transform: translateY(-5px);
    }

    .booking-card img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .room-name {
        font-weight: bold;
        font-size: 16px;
    }

    .building-name,
    .booker-name {
        font-size: 14px;
        color: #666;
    }

    .carousel-controls {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .icon-btn {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #333;
    }

    .stat-card {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        padding: 15px;
    }

    .stat-card .icon {
        background: #FFC107;
        color: #fff;
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 15px;
    }

    .stat-card .details h3 {
        margin: 0;
        font-size: 22px;
    }

    .stat-card .details p {
        margin: 0;
        font-size: 14px;
        color: #555;
    }

    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }

    @media (max-width: 768px) {
        .booking-card {
            min-width: 140px;
        }

        .stat-card .icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .stat-card .details h3 {
            font-size: 20px;
        }

        .chart-container {
            height: 200px;
        }
    }
</style>
