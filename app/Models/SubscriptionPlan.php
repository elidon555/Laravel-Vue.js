<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'user_id', 'price_id', 'features','interval', 'created_at','updated_at'];

    public function contentCreator()
    {
        return $this->hasOne(User::class);
    }

}
