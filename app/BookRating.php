<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookRating extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'rate',
    ];
}
