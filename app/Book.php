<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'book_type_id',
        'title',
        'description',
        'cover_url',
        'file_url',
        'is_public',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookType() {
        return $this->belongsTo(BookType::class);
    }

    public function bookComments() {
        return $this->hasMany(BookComment::class);
    }
}
