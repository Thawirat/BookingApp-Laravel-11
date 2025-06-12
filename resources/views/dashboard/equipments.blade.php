@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 fw-bold">รายการวัสดุ/อุปกรณ์</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                <i class="fas fa-plus"></i> เพิ่มอุปกรณ์
            </button>
        </div>
        <!-- ตารางรายการอุปกรณ์ -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ลำดับที่</th>
                                <th class="text-center">ชื่อ</th>
                                <th class="text-center">รายละเอียด</th>
                                <th class="text-center">จำนวนทั้งหมด</th>
                                <th class="text-center">คงเหลือ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($equipments as $equipment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->description ?? '-' }}</td>
                                    <td>{{ $equipment->quantity }}</td>
                                    <td>{{ $equipment->remaining }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editEquipmentModal-{{ $equipment->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDeleteEquipment({{ $equipment->id }}, '{{ $equipment->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteEquipmentForm{{ $equipment->id }}"
                                            action="{{ route('equipments.destroy', $equipment->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">ยังไม่มีอุปกรณ์ในระบบ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('components.modal.equipments.add-equipments')
    @include('components.modal.equipments.edit-equipments')
@endsection
<script>
    function confirmDeleteEquipment(id, name) {
        Swal.fire({
            title: 'ลบอุปกรณ์?',
            html: `คุณแน่ใจหรือไม่ว่าต้องการลบ <strong>${name}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteEquipmentForm${id}`).submit();
            }
        });
    }
</script>
