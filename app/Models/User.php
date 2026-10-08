<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    use HasApiTokens ; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function IdentityDocument()
    {
        return $this->hasMany(IdentityDocument::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

    public function rentalRequestsAsTenant()
    {
        return $this->hasMany(
            RentalRequest::class,
            'tenant_id'
        );
    }

    public function rentalRequestsAsLandlord()
    {
        return $this->hasMany(
            RentalRequest::class,
            'landlord_id'
        );
    }


    public function tenantContracts()
    {
        return $this->hasMany(
            Contract::class,
            'tenant_id'
        );
    }


    public function landlordContracts()
    {
        return $this->hasMany(
            Contract::class,
            'landlord_id'
        );
    }



    public function conversationsAsTenant()
    {
        return $this->hasMany(
            Conversation::class,
            'tenant_id'
        );
    }

    public function conversationsAsLandlord()
    {
        return $this->hasMany(
            Conversation::class,
            'landlord_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(
            Message::class,
            'sender_id'
        );
    }



    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }


    public function favoriteProperties()
    {
        return $this->belongsToMany(
            Property::class,
            'favorites'
        )->withTimestamps();
    }


    public function reviews()
    {
        return $this->hasMany(
            Review::class,
            'tenant_id'
        );
    }

    public function blocks()
    {
        return $this->hasMany(
            UserBlock::class,
            'blocker_id'
        );
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_blocks',
            'blocker_id',
            'blocked_id'
        )->withTimestamps();
    }

    public function blockedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_blocks',
            'blocked_id',
            'blocker_id'
        )->withTimestamps();
    }


    public function tenantReviews()
    {
        return $this->hasMany(
            TenantReview::class,
            'tenant_id'
        );
    }



    public function landlordReviews()
    {
        return $this->hasMany(
            TenantReview::class,
            'landlord_id'
        );
    }


}
