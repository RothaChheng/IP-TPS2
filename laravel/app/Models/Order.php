<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Databaase\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'order_date', 'total_price'];
}
