<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ShortUrl extends Model
{
     use HasFactory;
    protected $fillable = [
        'original_url',
        'short_code',
        'user_id',
        'company_id',
        'clicks',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    

}
