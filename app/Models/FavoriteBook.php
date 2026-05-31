<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteBook extends Model
{
    protected $table = 'favorite_books';
    protected $fillable = ['user_id', 'open_library_id', 'title', 'author', 'cover_url'];
}
