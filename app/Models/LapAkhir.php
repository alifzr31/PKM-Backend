<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'fl_lapakhir',
        'catatan',
        'status',
        'nip'
    ];
}
