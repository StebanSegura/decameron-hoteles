<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $table = 'hoteles';
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad_id',
        'nit',
        'numero_habitaciones',
    ];

    protected $casts = [
        'numero_habitaciones' => 'integer',
    ];

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function configuraciones(): HasMany
    {
        return $this->hasMany(HotelConfiguracion::class);
    }

    /** Suma de habitaciones ya configuradas para este hotel. */
    public function totalConfigurado(): int
    {
        return (int) $this->configuraciones()->sum('cantidad');
    }
}
