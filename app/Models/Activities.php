<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Activities extends Model
{
    use HasFactory;
    protected $table = 'activities'; // Ensure this matches the actual database table name
    protected $fillable = ['user_id', 'action', 'table_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
