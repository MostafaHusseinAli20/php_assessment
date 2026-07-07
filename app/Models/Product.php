<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'products';
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'status',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_DISCONTINUED = 'discontinued';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_DISCONTINUED,
    ];
}
