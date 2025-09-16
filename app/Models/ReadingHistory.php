<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $table = 'reading_history';

    protected $fillable = [
        'user_id',
        'comic_api_slug',
        'comic_api_title',
        'chapter_api_slug',
        'last_page_number',
        'last_read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
