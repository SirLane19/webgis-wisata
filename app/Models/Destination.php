<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Schedule;

class Destination extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'ticket_price',
        'photo',
        'category_id',
        'description' 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
