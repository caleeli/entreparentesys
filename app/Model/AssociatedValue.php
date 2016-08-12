<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AssociatedValue extends Model
{

    protected $fillable = [
        'dimension_id',
        'value',
    ];

    public function synonyms()
    {
        return $this->hasMany(\App\Model\Synonym::class);
    }
}
