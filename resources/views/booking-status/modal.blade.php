<!-- Modal for Booking Details -->
<div class="modal fade" id="detailsModal{{ $booking->id }}" tabindex="-1"
    aria-labelledby="detailsModalLabel{{ $booking->id }}" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
       <div class="modal-content border-0 shadow-lg rounded-4">
           <div class="modal-header bg-primary text-white rounded-top-4">
               <h5 class="modal-title" id="detailsModalLabel{{ $booking->id }}">
                   <i class="fas fa-info-circle me-2"></i> รายละเอียดการจองห้อง
               </h5>
               <button type="button" class="btn-close btn-close-white"
                       data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body p-4">
               <div class="row gy-4">
                   <!-- Booking Info -->
                   <div class="col-md-6">
                       <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลการจอง</h6>
                       <ul class="list-unstyled small">
                           <li><strong>รหัสการจอง:</strong> {{ $booking->id }}</li>
                           <li><strong>วันที่จอง:</strong> {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}</li>
                           <li><strong>เวลา:</strong>
                               {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} -
                               {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                           </li>
                           <li><strong>วันที่สิ้นสุด:</strong> {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}</li>
                           <li><strong>วัตถุประสงค์:</strong> {{ $booking->reason ?? 'ไม่ระบุ' }}</li>
                           <li><strong>จำนวนผู้เข้าร่วม:</strong> {{ $booking->attendees ?? 'ไม่ระบุ' }} คน</li>
                           <li><strong>สถานะการชำระเงิน:</strong>
                               <span class="badge px-2 py-1 {{ $booking->payment_status == 'ชำระแล้ว' ? 'bg-success' : 'bg-warning text-dark' }}">
                                   {{ $booking->payment_status }}
                               </span>
                           </li>
                       </ul>
                   </div>

                   <!-- Booker Info -->
                   <div class="col-md-6">
                       <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลผู้จอง</h6>
                       <ul class="list-unstyled small">
                           <li><strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}</li>
                           <li><strong>อีเมล:</strong> {{ $booking->external_email }}</li>
                           <li><strong>โทรศัพท์:</strong> {{ $booking->external_phone }}</li>
                           <li><strong>หน่วยงาน/แผนก:</strong> {{ $booking->department ?? 'ไม่ระบุ' }}</li>
                       </ul>
                   </div>

                   <!-- Room Info -->
                   <div class="col-12">
                       <h6 class="fw-semibold text-primary border-bottom pb-1 mb-3">ข้อมูลห้อง</h6>
                       <ul class="list-unstyled small">
                           <li><strong>อาคาร:</strong> {{ $booking->building_name }}</li>
                           <li><strong>ห้อง:</strong> {{ $booking->room_name }}</li>
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
