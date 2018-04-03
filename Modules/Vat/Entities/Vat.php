<?php

namespace Modules\Vat\Entities;

use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
    protected $fillable = ['id','uuid','name','percent','updated_date','created_at'];

    public function Products()
    {
        return $this->hasMany(Product::class);
    }

}
