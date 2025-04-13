<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $connection = 'supabase';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'email', 'password'];

    public function imageTargets()
    {
        return $this->hasMany(ImageTarget::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
