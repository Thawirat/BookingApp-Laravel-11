<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['building_name', 'citizen_save', 'date_save', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_buildings', 'building_id', 'user_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
