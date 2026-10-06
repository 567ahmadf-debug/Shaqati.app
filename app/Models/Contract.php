<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'rental_price',
        'currency',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rental_price' => 'decimal:2',
    ];

    public function rentalRequest()
    {
        return $this->belongsTo(
            RentalRequest::class
        );
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(
            User::class,
            'tenant_id'
        );
    }

    public function landlord()
    {
        return $this->belongsTo(
            User::class,
            'landlord_id'
        );
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function tenantReview()
{
    return $this->hasOne(TenantReview::class);
}
}
