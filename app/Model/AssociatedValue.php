<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AssociatedValue extends Model
{

    public function synonyms()
    {
        return $this->hasMany(\App\Model\Synonym::class);
    }
}
