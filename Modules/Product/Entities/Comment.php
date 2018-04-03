<?php

namespace Modules\Product\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'uuid',
        'published',
        'product_id',
        'user_id',
        'firstname',
        'lastname',
        'comment'
    ];

    protected $table = 'products_comments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
