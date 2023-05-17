<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email','address','city','state','postal_code', 'country','created_at','updated_at'];

    public function payments()
    {
        return $this->hasMany(Payment::class,'user_id','user_id');
    }

}
