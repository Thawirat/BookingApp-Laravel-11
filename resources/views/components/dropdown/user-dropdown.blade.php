@php
    $statusConfig = match ($user->status) {
        'pending' => [
            'color' => 'bg-amber-100 text-amber-800 border-amber-200',
            'text' => 'รออนุมัติ',
        ],
        'active' => [
            'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'text' => 'อนุมัติแล้ว',
        ],
        'rejected' => [
            'color' => 'bg-rose-100 text-rose-800 border-rose-200',
            'text' => 'ไม่อนุมัติ',
        ],
        default => [
            'color' => 'bg-gray-100 text-gray-800 border-gray-200',
            'text' => 'ไม่ระบุ',
        ],
    };
@endphp
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold border rounded-xl
                   {{ $statusConfig['color'] }}
                   hover:shadow transition-all duration-150 focus:outline-none min-w-[100px]"
        style="border-radius: 0.5rem !important">
        <span>{{ $statusConfig['text'] }}</span>
        <svg class="ml-1 w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : 'rotate-0'"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition x-cloak
        class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-md z-50 overflow-hidden">
        <form method="POST" action="{{ route('users.updateStatus', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="py-2">
                @foreach ([
        'pending' => ['text' => 'รออนุมัติ', 'color' => 'amber'],
        'active' => ['text' => 'อนุมัติแล้ว', 'color' => 'emerald'],
        'rejected' => ['text' => 'ไม่อนุมัติ', 'color' => 'rose'],
    ] as $value => $data)
                    <button type="submit" name="status" value="{{ $value }}"
                        class="w-full text-left px-4 py-2 text-xs font-medium text-{{ $data['color'] }}-700 hover:bg-{{ $data['color'] }}-50
                                   transition-colors duration-100 flex items-center group rounded-md mx-1 mb-1">
                        <div class="w-2.5 h-2.5 bg-{{ $data['color'] }}-400 rounded-full mr-2">
                        </div>
                        {{ $data['text'] }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>
</div>
