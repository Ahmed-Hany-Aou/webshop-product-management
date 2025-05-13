<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;///// Dont forget to add this so that the factory can be used and dont through an error bad call exception
    protected $fillable = ['name', 'description', 'price', 'stock_quantity'];

}
