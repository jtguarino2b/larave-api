<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;
    
    protected $table = 'blogs';

    protected $fillable = [
        'users_id',
        'title',
        'article'
    ];

    
}
