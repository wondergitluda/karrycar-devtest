<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'last_name',
        'email',
        'phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'referent_shipment', 'referent_id', 'shipment_id')
            ->withPivot('id', 'scope')
            ->withTimestamps();
    }

    public function startShipments()
    {
        return $this->belongsToMany(Shipment::class, 'referent_shipment', 'referent_id', 'shipment_id')
            ->withPivot('id', 'scope')
            ->withTimestamps()
            ->wherePivot('scope', 'start');
    }

    public function endShipments()
    {
        return $this->belongsToMany(Shipment::class, 'referent_shipment', 'referent_id', 'shipment_id')
            ->withPivot('id', 'scope')
            ->withTimestamps()
            ->wherePivot('scope', 'end');
    }
}
