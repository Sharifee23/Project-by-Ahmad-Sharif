<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{

    use HasFactory;

    protected $table = 'markets';
    
    protected $fillable = ['name', 'state_id'];

    public function state()
    {
        return $this->belongsTo(states::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'market_id');
    }

}
