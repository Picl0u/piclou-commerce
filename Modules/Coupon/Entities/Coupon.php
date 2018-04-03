<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'coupon',
        'percent',
        'price',
        'use_max',
        'amount_min',
        'begin',
        'end'
    ];

    public function CouponUsers()
    {
        return $this->hasMany(CouponUser::class);
    }

    public function CouponProducts()
    {
        return $this->hasMany(CouponProduct::class);
    }

}
