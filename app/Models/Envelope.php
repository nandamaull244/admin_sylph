<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envelope extends Model
{
    use HasFactory;
    protected $table = 'envelope';
    
    protected $fillable = [
        'hash_id',
        'pengirim',
        'penerima',
        'body',
    ];
}
