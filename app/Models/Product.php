<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Product ID (auto-generated)",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Product name (required)",
 *         example="Smartphone X1"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Product description (optional)",
 *         example="Latest smartphone with advanced features"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Product price (required)",
 *         example=599.99
 *     ),
 *     @OA\Property(
 *         property="stock_quantity",
 *         type="integer",
 *         description="Available stock quantity (required)",
 *         example=100
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date",
 *         example="2023-01-01T12:00:00Z"
 *     )
 * )
 */
class Product extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
    ];
}
