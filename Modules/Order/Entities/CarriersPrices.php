<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class CarriersPrices extends Model
{
    protected $fillable = [
        'uuid',
        'carriers_id',
        'country_id',
        'price_min',
        'price_max',
        'price',
        'key'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Carrier()
    {
        return $this->belongsTo(Carriers::class, 'carriers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Country()
    {
        return $this->belongsTo(Countries::class);
    }

}
