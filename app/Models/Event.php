<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','date','time','location','available_seats','price', 'status','created_at','updated_at','deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_at = now();
        });

        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    // public function getAvailableSeatsAttribute()
    // {
    //     $bookedSeats = $this->bookings()->sum('seats');
    //     return $this->total_seats - $bookedSeats;
    // }

}
