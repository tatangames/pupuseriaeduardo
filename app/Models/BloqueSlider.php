<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueSlider extends Model
{
    use HasFactory;
    protected $table = 'bloque_slider';
    public $timestamps = false;

    protected $fillable = [
        'posicion',
    ];
}
