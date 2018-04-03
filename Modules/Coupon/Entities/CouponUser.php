<?php

namespace Modules\Coupon\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $fillable = [
        'uuid',
        'coupon_id',
        'user_id',
        'use'
    ];

    protected $table = "coupon_users";

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
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
