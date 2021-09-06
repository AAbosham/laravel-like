<?php

namespace Aabosham\Likeable\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /**
     * Fillable fields for a favorite.
     *
     * @var array
     */
    protected $fillable = ['user_id'];
}
