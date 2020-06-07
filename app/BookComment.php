<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'comment',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
