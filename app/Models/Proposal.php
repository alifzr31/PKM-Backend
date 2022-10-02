<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'dana',
        'fl_proposal',
        'catatan_revisi',
        // 'fl_proposal_external',
        'status',
        'nip'
    ];
}
