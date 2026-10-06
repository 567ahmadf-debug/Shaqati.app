<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalRequest extends Model
{
    protected $fillable = [
        'property_id',
        'tenant_id',
        'landlord_id',
        'status',
        'message',
        'submitted_at',
        'responded_at',
        'rejection_reason',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}

