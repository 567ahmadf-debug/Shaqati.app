<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nette\Schema\Message;

class Conversation extends Model
{
    protected $fillable = [
        'property_id',
        'rental_request_id',
    ];

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

    public function rentalRequest()
    {
        return $this->belongsTo(
            RentalRequest::class
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}