<?php

namespace Modules\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletters extends Model
{
    protected $fillable = ['uuid', 'active', 'email', 'firstname', 'lastname'];
}
