<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'dana',
        'fl_proposal',
        'catatan_uppm',
        'status',
        'nip'
    ];
}
