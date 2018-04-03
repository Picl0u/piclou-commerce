<?php

namespace Modules\Product\Entities;

use App\Http\Picl0u\Attributes\HasAttributes;
use Illuminate\Database\Eloquent\Model;

class ProductsAttribute extends Model
{
    use HasAttributes;

    protected $fillable = [
        'uuid',
        'product_id',
        'stock_brut',
        'declinaisons',
        'reference',
        'ean_code',
        'upc_code',
        'isbn_code',
    ];

    public $attr = [
        'declinaisons'
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

}
