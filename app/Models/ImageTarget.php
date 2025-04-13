<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTarget extends Model
{
    protected $connection = 'supabase';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'name', 'image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
