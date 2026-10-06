<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $casts = [
        'metadata' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(
            User::class,
            'actor_id'
        );
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
