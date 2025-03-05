<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Databaase\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
}
