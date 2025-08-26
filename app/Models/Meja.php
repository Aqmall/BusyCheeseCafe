<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $primaryKey = 'nomorMeja';
    public $incrementing = false;
    protected $keyType = 'string';

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'nomorMeja', 'nomorMeja');
    }
}