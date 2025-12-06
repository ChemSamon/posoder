<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['receipt_number', 'total', 'discount', 'total_after_discount','note'];

    public function items()
{
    return $this->hasMany(OrderItem::class);
}


    
}
