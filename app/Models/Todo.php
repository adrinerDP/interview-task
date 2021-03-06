<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id', 'user_id', 'title', 'completed'];

    public function scopeSearch($query, $needle)
    {
        return $query->whereRaw($needle);
    }
}
