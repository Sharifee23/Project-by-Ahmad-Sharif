<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Temp_record extends Model
{
    use HasFactory;

    protected $table = 'temp_records';


    protected $fillable = [
        'product_id',
        'market_id',
        'price',
    ];



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}
