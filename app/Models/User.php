<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, Billable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class,'user_id','id')->where('stripe_status', 'active');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
