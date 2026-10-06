<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class IdentityDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_path',
        'document_type',
        'status',
        'rejection_reason',
        'verified_at'
    ];

    #[Override]
    public function casts()
    {
        return [
            'verified_at' => 'datetime'
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
