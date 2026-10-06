<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reason',
        'description',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(
            User::class,
            'reporter_id'
        );
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }
}
