<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PENDING_REVIEW = 'pending_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const AVAILABILITY_AVAILABLE = 'available';

    public const AVAILABILITY_UNAVAILABLE = 'unavailable';

    protected $fillable = [
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


    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim($term ?? '');

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term) {
            $builder->where('title', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%");
        });
    }
}
