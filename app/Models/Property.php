<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'property_type',
        'rental_price',
        'currency',
        'area',
        'bedrooms',
        'bathrooms',
        'address',
        'city',
        'latitude',
        'longitude',
        'status',
        'availability_status',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'area' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function rentalRequests()
    {
        return $this->hasMany(RentalRequest::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }


    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }




    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }




    public function favoritedBy()
    {
        return $this->belongsToMany(
            User::class,
            'favorites'
        )->withTimestamps();
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
