<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function referents()
    {
        return $this->belongsToMany(Referent::class, 'referent_shipment', 'shipment_id', 'referent_id')
            ->withPivot('id', 'scope')
            ->withTimestamps();
    }

    public function startReferents()
    {
        return $this->belongsToMany(Referent::class, 'referent_shipment', 'shipment_id', 'referent_id')
            ->withPivot('id', 'scope')
            ->withTimestamps()
            ->wherePivot('scope', 'start');
    }

    public function endReferents()
    {
        return $this->belongsToMany(Referent::class, 'referent_shipment', 'shipment_id', 'referent_id')
            ->withPivot('id', 'scope')
            ->withTimestamps()
            ->wherePivot('scope', 'end');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
