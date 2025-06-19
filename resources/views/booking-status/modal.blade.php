<!-- Modal for Booking Details -->
<div class="modal fade" id="detailsModal{{ $booking->id }}" tabindex="-1"
    aria-labelledby="detailsModalLabel{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="detailsModalLabel{{ $booking->id }}">
                    <i class="fas fa-info-circle me-2"></i> รายละเอียดการจองห้อง
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row gy-4">
                    <!-- Booking Info -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลการจอง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>รหัสการจอง:</strong> {{ $booking->booking_id }}</li>
                            <li><strong>วันที่จอง:</strong>
                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}</li>
                            <li><strong>เวลา:</strong>
                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                            </li>
                            <li><strong>วันที่สิ้นสุด:</strong>
                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}</li>
                            <li><strong>วัตถุประสงค์:</strong> {{ $booking->reason ?? 'ไม่ระบุ' }}</li>
                            <li><strong>จำนวนผู้เข้าร่วม:</strong> {{ $booking->participant_count ?? 'ไม่ระบุ' }} คน</li>
                            <li><strong>รายละเอียดเพิ่มเติม:</strong> {{ $booking->booker_info ?? 'ไม่ระบุ' }}</li>
                            {{-- <li><strong>สถานะการชำระเงิน:</strong>
                                <span
                                    class="badge dropdown-toggle
                                    @if ($booking->payment_status == 'paid') bg-success
                                    @elseif($booking->payment_status == 'pending') bg-info text-dark
                                    @elseif($booking->payment_status == 'cancelled') bg-danger
                                    @else bg-warning text-dark @endif"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="cursor: pointer;">
                                    {{ match ($booking->payment_status) {
                                        'unpaid' => 'ยังไม่ชำระ',
                                        'paid' => 'ชำระเงินแล้ว',
                                        'partial' => 'ชำระบางส่วน',
                                        'pending' => 'รอตรวจสอบ',
                                        'cancelled' => 'ยกเลิกการชำระ',
                                    } }}
                                </span>
                            </li> --}}
                        </ul>
                    </div>
                    <!-- Booker Info -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลผู้จอง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}</li>
                            <li><strong>อีเมล:</strong> {{ $booking->external_email }}</li>
                            <li><strong>โทรศัพท์:</strong> {{ $booking->external_phone }}</li>
                            <li><strong>ตำแหน่ง:</strong> {{ $booking->external_position ?? 'ไม่ระบุ' }}</li>
                            <li><strong>ที่อยู่/หน่วยงาน:</strong> {{ $booking->external_address ?? 'ไม่ระบุ' }}</li>
                        </ul>
                    </div>
                    <!-- Room Info -->
                    <div class="col-12">
                        <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลห้อง</h6>
                        <ul class="list-unstyled small">
                            <li><strong>อาคาร:</strong> {{ $booking->building_name }}</li>
                            <li><strong>ห้อง:</strong> {{ $booking->room_name }}</li>
                            {{-- <li><strong>รายละเอียด:</strong> {{$booking->room_id->room_details}}</li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> ปิด
                </button>
            </div>
        </div>
    </div>
</div>
