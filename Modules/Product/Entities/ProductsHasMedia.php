<?php

namespace Modules\Product\Entities;

use App\Http\Picl0u\Medias\HasMedias;
use Illuminate\Database\Eloquent\Model;

class ProductsHasMedia extends Model
{
    use HasMedias;

    protected $fillable = ['id', 'product_id', 'image', 'order'];

    protected $table = 'products_images';

    public $timestamps = false;

    public $medias = [
        'image',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
