<?php

namespace Modules\Order\Entities;

use App\Http\Picl0u\Medias\HasMedias;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class OrdersProducts extends Model
{
    use HasMedias;

    protected $fillable = [
        'uuid',
        'order_id',
        'product_id',
        'ref',
        'name',
        'image',
        'quantity',
        'price_ht',
        'price_ttc',
    ];

    public $medias = ['image'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrderReturns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

}
