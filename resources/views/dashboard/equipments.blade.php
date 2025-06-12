@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">รายการวัสดุ/อุปกรณ์</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                <i class="fas fa-plus"></i> เพิ่มอุปกรณ์
            </button>
            @include('components.modal.equipments.add-equipments')
        </div>
        <form method="GET" action="{{ route('equipments.index') }}" class="row g-2 mb-3 ">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="ค้นหาด้วยชื่อหรือคำอธิบาย">
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="">-- เรียงตามจำนวน --</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>น้อย → มาก</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>มาก → น้อย</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> ค้นหา / กรอง</button>
                <a href="{{ route('equipments.index') }}" class="btn btn-danger">รีเซ็ต</a>
            </div>
        </form>
        <!-- ตารางรายการอุปกรณ์ -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $equipment->name }}</td>
                                    <td class="text-center">{{ $equipment->description ?? '-' }}</td>
                                    <td class="text-center">{{ $equipment->quantity }}</td>
                                    <td class="text-center">{{ $equipment->remaining }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editEquipmentModal-{{ $equipment->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDeleteEquipment({{ $equipment->id }}, '{{ $equipment->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <!-- Hidden form for delete -->
                                        <form id="deleteEquipmentForm{{ $equipment->id }}"
                                            action="{{ route('equipments.destroy', $equipment->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @include('components.modal.equipments.edit-equipments')
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">ยังไม่มีอุปกรณ์ในระบบ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $equipments->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
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
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteEquipmentForm${id}`).submit();
            }
        });
    }
</script>
