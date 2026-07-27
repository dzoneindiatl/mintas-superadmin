<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCollection extends Model
{
    use HasFactory;
     protected $fillable = [
        'title',
        'description',
        'image',
        'collection_type',
        'slug',
        'is_active',
        'show_on_home',
        'show_on_menu'
    ];
}
