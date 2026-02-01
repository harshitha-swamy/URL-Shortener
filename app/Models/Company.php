<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Users in this company
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // URLs created by this company's users
    public function urls()
    {
        return $this->hasManyThrough(ShortUrl::class, User::class);
    }
}
