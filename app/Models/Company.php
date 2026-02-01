<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

   

    public function shortUrls()
    {
        return $this->hasManyThrough(
            ShortUrl::class,
            User::class,
            'company_id', // FK on users table
            'user_id',    // FK on short_urls table
            'id',         // PK on companies
            'id'          // PK on users
        );
    }
}
