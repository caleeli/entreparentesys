<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $fillable = [
        'name',
    ];

    public function associatedValues()
    {
        return $this->hasMany(\App\Model\AssociatedValue::class);
    }
}
