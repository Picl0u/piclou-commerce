<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Status extends Model
{
    use HasTranslations;

    protected $fillable = [
        'uuid',
        'name',
        'color',
        'order_accept',
        'order_refuse'
    ];

    public $translatable = [
      'name'
    ];


    public $translatableInputs = [
        'name' => [
            'label' => 'Nom du statut',
            'type' => 'text'
        ]
    ];

    protected $table = 'status';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrdersStatus()
    {
        return $this->hasMany(OrdersStatus::class);
    }

}
