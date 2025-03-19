<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes; //Enable soft deletes

    protected $dates = ['deleted_at']; // Ensure deleted_at is treated as a date

    use HasFactory;
    protected $fillable = ['customer_id', 'order_date', 'total_price'];
    protected $table = 'orders';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected function orderDate(): Attribute
    {
        return Attribute::make(
            // Mutator: Convert input format to MySQL format before saving
            set: fn ($value) => Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s'),

            // Accessor: Convert database format to user format when retrieving
            get: fn ($value) => Carbon::parse($value)->format('d/m/Y H:i:s')
        );
    }
}
