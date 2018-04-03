<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class Carriers extends Model
{
    protected $fillable = [
        'uuid',
        'free',
        'price',
        'weight',
        'name',
        'delay',
        'image',
        'url',
        'max_weight',
        'max_width',
        'max_height',
        'max_length',
        'default',
        'default_price'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CarriersPrices()
    {
        return $this->hasMany(CarriersPrices::class, 'carriers_id');
    }
}
