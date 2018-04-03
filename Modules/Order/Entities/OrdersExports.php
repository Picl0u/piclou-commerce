<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrdersExports extends Model
{
    protected $fillable = [
        'uuid',
        'begin',
        'end',
        'fileName',
    ];
}
