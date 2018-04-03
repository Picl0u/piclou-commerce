<?php
namespace Modules\ShoppingCart\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
    protected $guarded = [];

    protected $table = 'shoppingcart';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
