<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดการจองห้อง</title>
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
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }

        .university-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: 3px solid #0056b3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0 5px;
            color: #333;
        }

        h2 {
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0 20px;
            color: #007bff;
        }

        .document-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .doc-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .doc-date {
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .content-container {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 0 5px 5px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
            padding: 8px 0;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .label {
            font-weight: bold;
            color: #495057;
            width: 150px;
            flex-shrink: 0;
        }

        .value {
            flex: 1;
            color: #333;
            padding: 8px 12px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-left: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin-left: 15px;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .payment-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .payment-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .payment-unpaid {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .payment-cancelled {
            background: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        .booking-summary {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 15px;
            text-align: center;
        }

        .duration-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .duration-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #2196f3;
        }

        .duration-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .duration-value {
            font-size: 16px;
            font-weight: bold;
            color: #1976d2;
        }

        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .print-info {
            font-size: 12px;
            color: #666;
        }

        .print-info div {
            margin-bottom: 3px;
        }

        .signature-section {
            text-align: center;
            min-width: 250px;
        }

        .signature-box {
            border: 2px solid #333;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
        }

        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            height: 60px;
            margin: 20px auto 15px;
            background: white;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .signature-name {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .signature-date {
            font-size: 14px;
            color: #666;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            text-align: center;
            margin: 0 auto 10px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0,123,255,0.05);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }

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
    <div class="watermark">OFFICIAL</div>

    <div class="header">
        <div class="university-logo">LOGO</div>
        <h1>มหาวิทยาลัยตัวอย่าง</h1>
        <h2>ใบรายละเอียดการจองห้อง</h2>
    </div>

    <div class="document-info">
        <div class="doc-number">เลขที่เอกสาร: BK-{{ $booking->booking_id }}-{{ date('Ymd') }}</div>
        <div class="doc-date">วันที่ออกเอกสาร: {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y H:i:s') }} น.</div>
    </div>

    <div class="content-container">
        <div class="section">
            <div class="section-title">🏢 ข้อมูลการจองห้อง</div>
            <div class="info-row">
                <div class="label">รหัสการจอง:</div>
                <div class="value">{{ $booking->booking_id }}</div>
            </div>
            <div class="info-row">
                <div class="label">ชื่อห้อง:</div>
                <div class="value">{{ $booking->room_name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">อาคาร:</div>
                <div class="value">{{ $booking->building_name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">วันที่ทำการจอง:</div>
                <div class="value">{{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>

        <div class="booking-summary">
            <div class="summary-title">📅 สรุปช่วงเวลาการใช้งาน</div>
            <div class="duration-info">
                <div class="duration-box">
                    <div class="duration-label">เริ่มต้น</div>
                    <div class="duration-value">{{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y') }}</div>
                    <div class="duration-value">{{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} น.</div>
                </div>
                <div class="duration-box">
                    <div class="duration-label">สิ้นสุด</div>
                    <div class="duration-value">{{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y') }}</div>
                    <div class="duration-value">{{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }} น.</div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 15px; font-weight: bold; color: #1976d2;">
                ระยะเวลาการใช้งาน:
                @php
                    $start = \Carbon\Carbon::parse($booking->booking_start);
                    $end = \Carbon\Carbon::parse($booking->booking_end);
                    $duration = $end->diffInHours($start);
                    $minutes = $end->diffInMinutes($start) % 60;
                @endphp
                {{ $duration }} ชั่วโมง {{ $minutes }} นาที
            </div>
        </div>

        <div class="section">
            <div class="section-title">📋 สถานะและการชำระเงิน</div>
            <div class="info-row">
                <div class="label">สถานะการจอง:</div>
                @php
                    $statusClass = [
                        'approved' => 'status-approved',
                        'confirmed' => 'status-approved',
                        'pending' => 'status-pending',
                        'cancelled' => 'status-cancelled',
                        'rejected' => 'status-cancelled'
                    ][$booking->status->status_name ?? ''] ?? 'status-pending';
                @endphp
                <div class="status-badge {{ $statusClass }}">
                    {{ $booking->status->status_name ?? '-' }}
                </div>
            </div>
            <div class="info-row">
                <div class="label">สถานะการชำระเงิน:</div>
                @php
                    $paymentData = [
                        'paid' => ['text' => 'ชำระแล้ว', 'class' => 'payment-paid'],
                        'pending' => ['text' => 'รอตรวจสอบ', 'class' => 'payment-pending'],
                        'unpaid' => ['text' => 'ยังไม่ชำระ', 'class' => 'payment-unpaid'],
                        'cancelled' => ['text' => 'ยกเลิก', 'class' => 'payment-cancelled']
                    ][$booking->payment_status] ?? ['text' => 'ไม่ทราบ', 'class' => 'payment-pending'];
                @endphp
                <div class="status-badge {{ $paymentData['class'] }}">
                    {{ $paymentData['text'] }}
                </div>
            </div>
        </div>

        @if(isset($booking->notes) && $booking->notes)
        <div class="section">
            <div class="section-title">📝 หมายเหตุ</div>
            <div class="info-row">
                <div class="value" style="margin-left: 0; padding: 15px;">
                    {{ $booking->notes }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <div class="print-info">
            <div><strong>ข้อมูลการพิมพ์:</strong></div>
            <div>พิมพ์โดย: {{ Auth::user()->name ?? 'ระบบ' }}</div>
            <div>วันเวลา: {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y H:i:s') }} น.</div>
            <div>IP Address: {{ request()->ip() ?? 'N/A' }}</div>
            <div>เอกสารนี้ออกโดยระบบจองห้องออนไลน์</div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="qr-code">
                    QR<br>CODE
                </div>
                <div class="signature-line"></div>
                <div class="signature-label">ผู้รับรองเอกสาร</div>
                <div class="signature-name">{{ Auth::user()->name ?? 'ผู้ใช้งาน' }}</div>
                <div class="signature-date">{{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
