<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SharedReport extends Model
{

    public function report()
    {
        return $this->belongsTo('App\Model\Report');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
