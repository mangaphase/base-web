<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'comic_api_id',
        'chapter_api_id',
        'content',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
