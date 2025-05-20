<?php

namespace App\Enums;

enum BookingStatus: int
{
    case PENDING = 3;
    case APPROVED = 4;
    case REJECTED = 5;
    case COMPLETED = 6;

    public static function options(): array
    {
        return [
            self::APPROVED->value => ['label' => 'อนุมัติการจอง', 'class' => 'text-success', 'icon' => 'fas fa-check-circle'],
            self::REJECTED->value => ['label' => 'ยกเลิกการจอง', 'class' => 'text-danger', 'icon' => 'fas fa-times-circle'],
            self::PENDING->value  => ['label' => 'รอดำเนินการ', 'class' => 'text-warning', 'icon' => 'fas fa-clock'],
            self::COMPLETED->value => ['label' => 'ดำเนินการเสร็จสิ้น', 'class' => 'text-secondary', 'icon' => 'fas fa-check-double'],
        ];
    }
}
