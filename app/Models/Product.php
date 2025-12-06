<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'name', 'price', 'description', 'image', 'category_id'
];

// Optional: Relationship
public function category()
{
    return $this->belongsTo(Category::class);
}

}
