@foreach ($buildings as $building)
    <div class="col">
        <div class="card h-100 border-0 shadow-sm">
            <div class="position-relative">
                <img alt="ภาพ{{ $building->building_name }}" class="card-img-top"
                    src="{{ $building->image ? asset('storage/' . $building->image) : asset('images/no-picture.jpg') }}"
                    style="height: 180px; object-fit: cover;" />
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-success">
                        <i class="fas fa-door-open me-1"></i>{{ $building->rooms->count() }} ห้อง
                    </span>
                </div>
            </div>
            <div class="card-body px-3 py-2"> {{-- เพิ่ม padding ด้านใน --}}
                <h5 class="card-title">{{ $building->building_name }}</h5>
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-user-edit me-1"></i>บันทึกโดย:
                    {{ $building->citizen_save }}
                </p>
                <div class="btn-group w-100" role="group">
                    @if (Auth::user()->role === 'admin')
                        <button class="btn btn-sm btn-warning flex-grow-1"
                            onclick="openEditBuildingModal('{{ $building->id }}', '{{ $building->building_name }}', '{{ $building->citizen_save }}')">
                            <i class="fas fa-edit me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger flex-grow-1"
                            onclick="confirmDeleteBuilding('{{ $building->id }}', '{{ $building->building_name }}')">
                            <i class="fas fa-trash me-1"></i>
                        </button>
                    @endif
                    <button class="btn btn-sm btn-info flex-grow-1"
                        onclick="window.location.href='{{ route('manage_rooms.show', $building->id) }}'">
                        <i class="fas fa-door-open me-1"></i> ดูห้อง
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
