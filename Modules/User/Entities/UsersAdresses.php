<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Countries;

class UsersAdresses extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'delivery',
        'billing',
        'gender',
        'firstname',
        'lastname',
        'address',
        'additional_address',
        'zip_code',
        'city',
        'phone',
        'country_id'
    ];

    protected $with = [
      'Country'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Country()
    {
        return $this->belongsTo(Countries::class);
    }

}
