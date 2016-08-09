<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SharedVariable extends Model
{

    public function statisticalVariable()
    {
        return $this->belongsTo('App\Model\StatisticalVariable');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
