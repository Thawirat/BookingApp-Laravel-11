<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายละเอียดการจอง {{ $booking->room_name }}</title>
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

        body {
            font-family: "THSarabun", sans-serif;
            font-size: 16pt;
            line-height: 1.6;
            padding: 30px;
        }

        h1, h3 {
            text-align: center;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            height: 80px;
        }

        .doc-number {
            text-align: right;
            font-size: 14pt;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18pt;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 6px;
        }

        table.signature-table td {
            padding: 30px 10px;
            vertical-align: top;
            height: 160px;
        }

        .center {
            text-align: center;
        }

        .note {
            font-size: 14pt;
            margin-top: 40px;
        }

        .box {
            border: 1px solid #aaa;
            padding: 15px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="doc-number">
        เลขที่หนังสือ: ศธ. {{ date('Y') }}/{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
    </div>
    <div class="logo">
        <img src="{{ public_path('images/snru.png') }}" alt="University Logo">
    </div>
    <h3>รายละเอียดการจองห้องประชุม<br>{{ $booking->room_name }}</h3>

    <div class="section">
        <div class="section-title">ข้อมูลการจอง</div>
        <div class="grid">
            <div class="box">
                <ul>
                    <li><strong>รหัสการจอง:</strong> {{ $booking->id }}</li>
                    <li><strong>วันที่จอง:</strong> {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}</li>
                    <li><strong>เวลา:</strong> {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</li>
                    <li><strong>วันที่สิ้นสุด:</strong> {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}</li>
                    <li><strong>สถานะการชำระเงิน:</strong>
                        {{ match ($booking->payment_status) {
                            'unpaid' => 'ยังไม่ชำระ',
                            'paid' => 'ชำระเงินแล้ว',
                            'pending' => 'รอตรวจสอบ',
                            'cancelled' => 'ยกเลิกการชำระ',
                        } }}
                    </li>
                </ul>
            </div>
            <div class="box">
                <ul>
                    <li><strong>วัตถุประสงค์:</strong> {{ $booking->reason ?? 'ไม่ระบุ' }}</li>
                    <li><strong>จำนวนผู้เข้าร่วม:</strong> {{ $booking->participant_count ?? 'ไม่ระบุ' }} คน</li>
                    <li><strong>รายละเอียดเพิ่มเติม:</strong> {{ $booking->booker_info ?? 'ไม่ระบุ' }}</li>
                    <li><strong>อาคาร:</strong> {{ $booking->building_name }}</li>
                    <li><strong>ห้อง:</strong> {{ $booking->room_name }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ข้อมูลผู้จอง</div>
        <div class="box">
            <ul>
                <li><strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}</li>
                <li><strong>อีเมล:</strong> {{ $booking->external_email }}</li>
                <li><strong>โทรศัพท์:</strong> {{ $booking->external_phone }}</li>
                <li><strong>ตำแหน่ง:</strong> {{ $booking->external_position ?? 'ไม่ระบุ' }}</li>
                <li><strong>ที่อยู่/หน่วยงาน:</strong> {{ $booking->external_address ?? 'ไม่ระบุ' }}</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <div class="section-title center">แบบบันทึกการอนุมัติการใช้ห้อง</div>
        <table class="signature-table" width="100%">
            <tr>
                <td class="center">
                    <strong>ผู้อนุมัติ</strong><br><br><br>
                    ............................................................<br>
                    (.......................................................)<br>
                    ตำแหน่ง: ..................................................<br>
                    วันที่: ........../........../..........
                </td>
                <td class="center">
                    <strong>ผู้ขอใช้ห้อง</strong><br><br><br>
                    ............................................................<br>
                    ({{ $booking->external_name }})<br>
                    ตำแหน่ง: {{ $booking->external_position ?? '..................................................' }}<br>
                    วันที่: ........../........../..........
                </td>
            </tr>
        </table>
    </div>

    <div class="note">
        <strong>หมายเหตุ:</strong><br>
        โปรดแนบสำเนาหนังสือขออนุญาตและแนบแบบฟอร์มนี้พร้อมลายเซ็นให้ครบถ้วน เพื่อใช้ประกอบการอนุมัติการใช้ห้องประชุม
    </div>
</body>

</html>
