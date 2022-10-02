<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'publikasi',
        'fl_luaran',
        'artikel',
        'status',
        'nip'
    ];
}
