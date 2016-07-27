<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{

    public function associatedValues()
    {
        return $this->hasMany(\App\Model\AssociatedValue::class);
    }
}
