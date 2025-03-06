<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Databaase\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'customer_id', 'payment_date', 'payment_method', 'amount'];
}
