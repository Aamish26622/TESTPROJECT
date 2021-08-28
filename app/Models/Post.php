<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'website_id'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function postEmailUsers()
    {
        return $this->belongsToMany(User::class, 'post_email_users')->withTimestamps();
    }
}
