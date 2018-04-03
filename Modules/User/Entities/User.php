<?php

namespace Modules\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Order\Entities\OrderReturn;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Comment;
use Modules\ShoppingCart\Entities\Shoppingcart;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;

    use HasRoles;

    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'online',
        'gender',
        'newsletter',
        'uuid',
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'role',
        'role_id',
        'guard_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function UsersAdresses()
    {
        return $this->hasMany(UsersAdresses::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Shoppingcart()
    {
        return $this->belongsTo(Shoppingcart::class);
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
    public function Role(){
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Comments()
    {
        return $this->hasMany(Comment::class);
    }

}
