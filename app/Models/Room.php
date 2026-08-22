<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    use HasFactory;
    protected $fillable = [
       'room_category_id',
       'number',
       'name',
       'description',
       'price',
       'capacity',
       'image',
       'status',
    ];
    public function category()
    {
        return $this->belongsTo(RoomCategory::class,'room_category_id');
    }
    public function  reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function  images()
    {
        return $this->hasMany(RoomImage::class);
    }
}
