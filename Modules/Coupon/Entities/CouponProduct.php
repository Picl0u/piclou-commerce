<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class CouponProduct extends Model
{
    protected $fillable = [
        'uuid',
        'coupon_id',
        'product_id',
        'use'
    ];

    protected $table = "coupon_products";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

}
