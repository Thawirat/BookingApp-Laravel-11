<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน #{{ $booking->id }}</title>
    <style>
        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun.ttf') }}") format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'THSarabun';
            src: url("{{ storage_path('fonts/THSarabun Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        body {
            font-family: "THSarabun", sans-serif;
            font-size: 16pt;
            line-height: 1.6;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            height: 70px;
        }

        .doc-title {
            font-size: 24pt;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .info-table, .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td,
        .summary-table td,
        .summary-table th {
            padding: 6px 10px;
            border: 1px solid #000;
        }

        .summary-table th {
            background-color: #f0f0f0;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }

        .signature p {
            margin-bottom: 60px;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 14pt;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <img src="file://{{ public_path('images/snru.png') }}" alt="University Logo" width="auto" height="auto">
        </div>
        <div class="doc-title">ใบเสร็จรับเงิน / ใบยืนยันการจองห้อง</div>
        <div>เลขที่ใบเสร็จ: {{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}/{{ date('Y') + 543 }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}</td>
            <td><strong>วันที่ออกใบเสร็จ:</strong> {{ now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>อีเมล:</strong> {{ $booking->external_email }}</td>
            <td><strong>โทรศัพท์:</strong> {{ $booking->external_phone }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>ที่อยู่/หน่วยงาน:</strong> {{ $booking->external_address ?? 'ไม่ระบุ' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>สถานะการจอง:</strong> {{ $booking->payment_status }}</td>
        </tr>

    </table>

    <table class="summary-table">
        <thead>
            <tr>
                <th>รายการ</th>
                <th class="right">จำนวนเงิน (บาท)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ค่าธรรมเนียมการใช้ห้อง "{{ $booking->room_name }}"</td>
                <td class="right">{{ number_format($booking->total_price, 2) }}</td>
            </tr>
            {{-- สามารถเพิ่มรายการอื่น ๆ ได้ เช่น ค่าทำความสะอาด ฯลฯ --}}
            <tr>
                <td class="right"><strong>รวมทั้งสิ้น</strong></td>
                <td class="right"><strong>{{ number_format($booking->total_price, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>............................................................</p>
        <div>(เจ้าหน้าที่รับเงิน)</div>
    </div>

    <div class="footer-note">
        * ใบเสร็จนี้ใช้เป็นหลักฐานการชำระเงินสำหรับการใช้ห้องประชุมเท่านั้น<br>
        * กรุณาเก็บใบเสร็จนี้ไว้เพื่อตรวจสอบในกรณีที่มีปัญหา
    </div>

</body>

</html>
