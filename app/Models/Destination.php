<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = [
        'name', 'address', 'latitude', 'longitude', 'ticket_price', 'photo', 'category_id'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
}
