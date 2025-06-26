<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ประวัติการจอง{{ $booking->room_name ?? 'ห้องประชุม' }} ID:
        {{ \Carbon\Carbon::now()->format('Y') + 543 }}/{{ $booking->ref_number }}</title>
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
            font-family: "THSarabun", "TH Sarabun New", Arial, sans-serif;
            font-size: 14pt;
            line-height: 1.0;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .page-container {
            box-sizing: border-box;
            margin: auto;
            background: #fff;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo img {
            height: 45px;
            margin-bottom: 8px;
        }

        .org-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .doc-header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .doc-header>div {
            display: table-cell;
            vertical-align: middle;
        }

        .doc-number {
            font-size: 12pt;
            font-weight: bold;
            width: 25%;
            text-align: left;
        }

        .doc-type {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            width: 50%;
            color: #000;
        }

        .doc-spacer {
            width: 25%;
        }

        .dotted-line {
            border-bottom: 1px solid #666;
            margin: 12px 0;
            height: 0;

        }

        .section {
            margin-bottom: 8px;
            clear: both;
        }

        .section-header {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .section-label {
            display: table-cell;
            font-weight: bold;
            width: 80px;
            color: #000;
            vertical-align: top;
        }

        .section-content {
            display: table-cell;
            border-bottom: 1px dotted #999;
            min-height: 18px;
            padding-bottom: 2px;
            padding-left: 5px;
            width: auto;
        }

        .content-box {
            border: 1px solid #000;
            padding: 12px;
            margin: 15px 0;
            background-color: #fff;
        }

        .content-text {
            text-align: justify;
            line-height: 1.0;
            color: #000;
        }

        .content-text p {
            margin: 0 0 8px 0;
        }

        .equipment-list {
            margin: 8px 0;
            padding-left: 18px;
            list-style: none;
        }

        .equipment-list li {
            margin-bottom: 4px;
            color: #000;
        }

        .right-align {
            text-align: right;
            margin-top: 20px;
            width: 100%;
        }

        .signature-section {
            text-align: center;
            margin-top: 15px;
            display: inline-block;
            width: 250px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 15px auto 8px auto;
            height: 0;
        }

        .contact-details {
            margin-top: 12px;
            font-size: 14pt;
            color: #000;
            text-align: right;
        }

        .contact-details div {
            margin-bottom: 3px;
        }

        .footer-section {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dotted #666;
        }

        .footer-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }

        .approval-box {
            border: 1px solid #000;
            padding: 12px;
            margin: 15px 0;
            background-color: #fff;
            text-align: center;
        }

        .approval-status {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }

        .checkbox-container {
            margin-bottom: 15px;
        }

        .checkbox-item {
            display: inline-block;
            margin: 0 30px;
            font-size: 14pt;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 8px;
            width: 12px;
            height: 12px;
        }

        .approval-signature {
            text-align: center;
            margin-top: 15px;
        }

        .approval-signature .signature-line {
            width: 180px;
            margin: 15px auto 8px auto;
        }

        /* สำหรับ DomPDF */
        table {
            border-collapse: collapse;
        }

        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .table-layout td {
            padding: 0;
            vertical-align: top;
        }

        .content-paragraph {
            margin-bottom: 8px;
        }

        .inline-strong {
            font-weight: bold;
        }

        .stamp-status {
            position: absolute;
            top: 0px;
            left: 0px;
            /* transform: rotate(-20deg); */
            font-size: 28pt;
            font-weight: bold;
            color: white;
            padding: 10px 30px;
            /* border: 3px solid #333; */
            /* border-radius: 8px; */
            /* background-color: #004cff; */
            /* opacity: 0.85; */
            z-index: 999;
        }
    </style>
</head>

<body>
    @php
        if ($booking->status_id === 6) {
            $stampText = 'ดำเนินการเสร็จสิ้น';
            $stampColor = '#004cff';
        } elseif ($booking->status_id === 5) {
            $stampText = 'ยกเลิกการจอง';
            $stampColor = '#dc3545';
        } elseif ($booking->status_id === 8) {
            $stampText = 'ไม่อนุมัติการจอง';
            $stampColor = '#6c757d';
        }
    @endphp

    <div class="stamp-status" style="background-color: {{ $stampColor }};">
        {{ $stampText }}
    </div>
    <div class="page-container">
        <div style="text-align: right; font-size: 12pt; font-weight: bold; margin: 15px 25px 5px 0;">
            <span class="inline-strong">รหัสการจอง:</span>
            {{ \Carbon\Carbon::now()->format('Y') + 543 }}/{{ $booking->ref_number }}
        </div>
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('images/snru.png') }}" alt="University Logo">
            </div>
            <div class="org-name">มหาวิทยาลัยราชภัฏสกลนคร</div>
        </div>
        <div class="dotted-line"></div>
        <div class="section">
            <div class="section-header">
                <div class="section-label">ส่วนราชการ</div>
                <div class="section-content">{{ $booking->external_address ?? '' }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-label">ที่</div>
                <div class="section-content"></div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-label">เรื่อง</div>
                <div class="section-content"> {{ $booking->title ?? 'ขออนุญาตใช้' . ($booking->room_name ?? 'ห้องประชุม') }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-label">วันที่</div>
                <div class="section-content">
                    {{ \Carbon\Carbon::now()->format('d/m/') . (\Carbon\Carbon::now()->format('Y') + 543) }}
                </div>
            </div>
        </div>
        <div class="section">
            <div class="section-header">
                <div class="section-label">เรียน</div>
                <div class="section-content"></div>
            </div>
        </div>

        <div class="content-box">
            <div class="content-text">
                <div class="content-paragraph">
                    <span class="inline-strong">ข้าพเจ้า</span>
                    {{ $booking->external_name ?? '.............................' }}
                    <span class="inline-strong">สังกัด</span>
                    {{ $booking->external_address ?? 'มหาวิทยาลัยราชภัฎสกลนคร' }}
                    มีความประสงค์จะขอใช้ห้อง <span
                        class="inline-strong">{{ $booking->room_name ?? '.............................' }}</span>
                </div>
                <div class="content-paragraph">
                    <span class="inline-strong">ในวันที่</span>
                    {{ $booking->booking_start ? \Carbon\Carbon::parse($booking->booking_start)->format('d/m/') . (\Carbon\Carbon::parse($booking->booking_start)->format('Y') + 543) : '.............................' }}
                    <span class="inline-strong">เวลา</span>
                    {{ $booking->booking_start ? \Carbon\Carbon::parse($booking->booking_start)->format('H:i') : '.....' }}
                    น.
                    <span class="inline-strong">ถึงวันที่</span>
                    {{ $booking->booking_end ? \Carbon\Carbon::parse($booking->booking_end)->format('d/m/') . (\Carbon\Carbon::parse($booking->booking_end)->format('Y') + 543) : '.............................' }}
                    <span class="inline-strong">เวลา</span>
                    {{ $booking->booking_end ? \Carbon\Carbon::parse($booking->booking_end)->format('H:i') : '.....' }}
                    น.
                </div>

                <div class="content-paragraph">
                    <span class="inline-strong">มีวัตถุประสงค์เพื่อ</span>
                    {{ $booking->reason ?? '.............................' }}
                    ซึ่งมีจำนวนผู้เข้าร่วมทั้งหมด <span
                        class="inline-strong">{{ $booking->participant_count ?? '.....' }} คน</span>
                </div>

                <div class="content-paragraph">
                    <span class="inline-strong">พร้อมนี้ ขอความอนุเคราะห์อุปกรณ์ดังนี้</span>
                </div>
                <ul class="equipment-list">
                    @forelse ($booking->room->equipments ?? [] as $equipment)
                        <li>- {{ $equipment->name }} {{ $equipment->pivot->quantity ?? '' }}
                            จำนวน {{ $equipment->quantity ?? '' }} ชิ้น</li>
                    @empty
                        ไม่มีอุปกรณ์
                    @endforelse
                </ul>

                <div class="content-paragraph">
                    <span class="inline-strong">รายละเอียดเพิ่มเติม:</span>
                    {{ $booking->booker_info ?? 'ไม่มีรายละเอียดเพิ่มเติม' }}
                </div>
            </div>
        </div>

        <div class="contact-details">
            <span>จึงเรียนมาเพื่อโปรดพิจารณา</span>
            <div>
                <strong>{{ $booking->external_name ?? '.............................' }}</strong>
            </div>
            <div><strong>ตำแหน่ง: </strong>
                {{ $booking->user->position ?? '.............................' }}
            </div>
            <div>
                <strong>โทรศัพท์: </strong>
                {{ $booking->external_phone ?? '.............................' }}
            </div>
            <div>
                <strong>อีเมล: </strong>
                {{ $booking->external_email ?? '.............................' }}
            </div>
        </div>

        <div class="footer-section">
            <div class="footer-title">สำหรับเจ้าหน้าที่</div>

            @if (isset($booking->status_id))
                @if ($booking->status_id === 6)
                    <div class="approval-box">
                        <div class="approval-status">อนุมัติให้ใช้{{ $booking->room_name }}</div>
                        <div class="approval-signature">
                            <div class="signature-line"></div>
                            <div style="font-weight: bold;">
                                ผู้อนุมัติ: {{ $booking->approver_name ?? '.............................' }}
                            </div>
                            <div style="font-weight: bold;">
                                ตำแหน่ง: {{ $booking->approver_position ?? '.............................' }}
                            </div>
                        </div>
                    </div>
                @elseif ($booking->status_id === 5)
                    <div class="approval-box">
                        <div class="approval-status">ยกเลิกการจอง{{ $booking->room_name }}</div>
                        <div style="font-weight: bold;">
                            ผู้อนุมัติ: {{ $booking->approver_name ?? 'ไม่มีการอนุมัติ' }}
                        </div>
                    </div>
                @elseif ($booking->status_id === 8)
                    <div class="approval-box">
                        <div class="approval-status">ไม่อนุมัติให้ใช้{{ $booking->room_name }}</div>
                        <div class="approval-signature">
                            <div style="font-weight: bold;">
                                ผู้อนุมัติ: {{ $booking->approver_name ?? '.............................' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="approval-box">
                        <div class="approval-status">อยู่ระหว่างรอดำเนินการ</div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>

</html>
