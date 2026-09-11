<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function hoteles()
    {
        return $this->hasMany(Hotel::class);
    }
}
