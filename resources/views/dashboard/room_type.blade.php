@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-bold mb-0">จัดการประเภทห้อง</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                เพิ่มประเภทห้อง
            </button>
        </div>

        {{-- Alert Messages (Hidden - Using SweetAlert2 instead) --}}
        <div class="d-none">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        {{-- ตารางประเภทห้อง --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 80px;">ลำดับ</th>
                                <th scope="col">ชื่อประเภทห้อง</th>
                                <th scope="col" class="text-center" style="width: 150px;">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roomTypes as $index => $type)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $type->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            {{-- ปุ่มแก้ไข --}}
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#editRoomTypeModal{{ $type->id }}" title="แก้ไข">
                                                แก้ไข
                                            </button>

                                            {{-- ปุ่มลบ --}}
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $type->id }}, '{{ $type->name }}')"
                                                title="ลบ">
                                                ลบ
                                            </button>
                                        </div>

                                        {{-- Hidden form for delete --}}
                                        <form id="deleteForm{{ $type->id }}"
                                            action="{{ route('room-types.destroy', $type->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted">
                                            <p class="mb-0">ไม่มีข้อมูลประเภทห้อง</p>
                                            <small>เริ่มต้นด้วยการเพิ่มประเภทห้องใหม่</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal เพิ่มประเภทห้อง --}}
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1" aria-labelledby="addRoomTypeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('room-types.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addRoomTypeLabel">
                        <i class="fas fa-plus-circle me-2"></i>เพิ่มประเภทห้อง
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addRoomTypeName" class="form-label">
                            ชื่อประเภทห้อง <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="addRoomTypeName"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="เช่น Standard Room, Deluxe Room" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>เพิ่มประเภทห้อง
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal แก้ไขประเภทห้อง --}}
    @foreach ($roomTypes as $type)
        <div class="modal fade" id="editRoomTypeModal{{ $type->id }}" tabindex="-1"
            aria-labelledby="editRoomTypeLabel{{ $type->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('room-types.update', $type->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editRoomTypeLabel{{ $type->id }}">
                            <i class="fas fa-edit me-2"></i>แก้ไขประเภทห้อง
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editRoomTypeName{{ $type->id }}" class="form-label">
                                ชื่อประเภทห้อง <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="editRoomTypeName{{ $type->id }}"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $type->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>บันทึกการเปลี่ยนแปลง
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to confirm delete with SweetAlert2
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'ยืนยันการลบ',
                html: `คุณแน่ใจหรือไม่ว่าต้องการลบประเภทห้อง<br><strong class="text-primary">"${name}"</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> ลบ',
                cancelButtonText: '<i class="fas fa-times me-1"></i> ยกเลิก',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // Auto-focus on modal inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on add modal
            document.getElementById('addRoomTypeModal').addEventListener('shown.bs.modal', function() {
                document.getElementById('addRoomTypeName').focus();
            });

            // Focus on edit modals
            @foreach ($roomTypes as $type)
                document.getElementById('editRoomTypeModal{{ $type->id }}').addEventListener('shown.bs.modal',
                    function() {
                        document.getElementById('editRoomTypeName{{ $type->id }}').focus();
                    });
            @endforeach

            // Show success message with SweetAlert2 if exists
            @if (session('success'))
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false
                });
            @endif

            // Show error message with SweetAlert2 if exists
            @if (session('error'))
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
@endpush
