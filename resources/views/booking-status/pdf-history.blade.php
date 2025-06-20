<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานประวัติการจองห้อง</title>
    <style>
        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @page {
            margin: 2cm;
            size: A4;
        }

        body {
            font-family: "THSarabun", sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .university-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: #f0f0f0;
            border: 2px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            margin: 10px 0 5px;
            color: #333;
        }

        h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0 15px;
            color: #555;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-section {
            flex: 1;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            margin-left: 10px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }

        th {
            background: linear-gradient(to bottom, #f8f8f8, #e8e8e8);
            border: 1px solid #666;
            padding: 12px 8px;
            font-weight: bold;
            text-align: center;
            color: #333;
        }

        td {
            border: 1px solid #888;
            padding: 10px 8px;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .status-approved {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc3545;
            font-weight: bold;
        }

        .payment-paid {
            color: #28a745;
            font-weight: bold;
        }

        .payment-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .payment-unpaid {
            color: #dc3545;
            font-weight: bold;
        }

        .summary {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .summary-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-section {
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            height: 60px;
            margin: 20px auto 10px;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-name {
            margin-bottom: 3px;
        }

        .signature-date {
            font-size: 14px;
            color: #666;
        }

        .print-info {
            font-size: 12px;
            color: #888;
            text-align: left;
        }

        .page-number {
            position: fixed;
            bottom: 1cm;
            right: 1cm;
            font-size: 12px;
            color: #666;
        }

        /* PDF specific styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="university-logo">LOGO</div>
        <h1>มหาวิทยาลัยตัวอย่าง</h1>
        <h2>รายงานประวัติการจองห้อง</h2>
    </div>

    <div class="report-info">
        <div class="info-section">
            <div><span class="info-label">ผู้ใช้งาน:</span><span class="info-value">{{ Auth::user()->name }}</span></div>
            <div><span class="info-label">รหัสผู้ใช้:</span><span class="info-value">{{ Auth::user()->id ?? 'N/A' }}</span></div>
        </div>
        <div class="info-section">
            <div><span class="info-label">วันที่พิมพ์:</span><span class="info-value">{{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y H:i:s') }}</span></div>
            <div><span class="info-label">เลขที่เอกสาร:</span><span class="info-value">RPT-{{ date('Ymd') }}-{{ str_pad(Auth::id() ?? 1, 4, '0', STR_PAD_LEFT) }}</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 12%;">รหัสการจอง</th>
                <th style="width: 18%;">ชื่อห้อง</th>
                <th style="width: 15%;">อาคาร</th>
                <th style="width: 10%;">วันที่จอง</th>
                <th style="width: 13%;">เริ่มต้น</th>
                <th style="width: 13%;">สิ้นสุด</th>
                <th style="width: 7%;">สถานะ</th>
                <th style="width: 7%;">ชำระเงิน</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $index => $booking)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $booking->booking_id }}</td>
                    <td class="left">{{ $booking->room_name ?? '-' }}</td>
                    <td class="left">{{ $booking->building_name ?? '-' }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y H:i') }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y H:i') }}</td>
                    <td class="center">
                        @php
                            $statusClass = [
                                'approved' => 'status-approved',
                                'pending' => 'status-pending',
                                'cancelled' => 'status-cancelled'
                            ][$booking->status->status_name ?? ''] ?? '';
                        @endphp
                        <span class="{{ $statusClass }}">{{ $booking->status->status_name ?? '-' }}</span>
                    </td>
                    <td class="center">
                        @php
                            $paymentData = [
                                'paid' => ['text' => 'ชำระแล้ว', 'class' => 'payment-paid'],
                                'pending' => ['text' => 'รอตรวจสอบ', 'class' => 'payment-pending'],
                                'unpaid' => ['text' => 'ยังไม่ชำระ', 'class' => 'payment-unpaid'],
                                'cancelled' => ['text' => 'ยกเลิก', 'class' => 'payment-cancelled']
                            ][$booking->payment_status] ?? ['text' => 'ไม่ทราบ', 'class' => ''];
                        @endphp
                        <span class="{{ $paymentData['class'] }}">{{ $paymentData['text'] }}</span>
                    </td>
                </tr>
            @endforeach
            @if(count($bookings) == 0)
                <tr>
                    <td colspan="9" class="center" style="padding: 30px; color: #666; font-style: italic;">
                        ไม่พบข้อมูลการจองห้อง
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-title">สรุปข้อมูลการจอง</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ count($bookings) }}</div>
                <div class="summary-label">จำนวนการจองทั้งหมด</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ collect($bookings)->where('payment_status', 'paid')->count() }}</div>
                <div class="summary-label">ชำระเงินแล้ว</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ collect($bookings)->where('payment_status', 'pending')->count() }}</div>
                <div class="summary-label">รอตรวจสอบ</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="print-info">
            <div>พิมพ์โดย: ระบบจองห้องออนไลน์</div>
            <div>IP Address: {{ request()->ip() ?? 'N/A' }}</div>
            <div>เวลาพิมพ์: {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y H:i:s') }} น.</div>
        </div>

        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="signature-label">ผู้พิมพ์รายงาน</div>
            <div class="signature-name">{{ Auth::user()->name }}</div>
            <div class="signature-date">{{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="page-number">หน้า 1</div>
</body>
</html>
