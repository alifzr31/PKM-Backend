<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'dana',
        'fl_hasilreview',
        'catatan',
        'status',
        'nip'
    ];
}
