<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantReview extends Model
{
    protected $fillable = [
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

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

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
