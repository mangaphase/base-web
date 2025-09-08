<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'comic_api_slug',
        'comic_api_title',
        'comic_api_thumbnail',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
