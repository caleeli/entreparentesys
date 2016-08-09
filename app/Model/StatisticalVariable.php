<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StatisticalVariable extends Model
{
    protected $fillable = [
        'name',
    ];

    public static function boot()
    {
        static::created(function(StatisticalVariable $model) {
            $sharedVariable = new SharedVariable();
            $sharedVariable->user_id = Auth::user() ? Auth::user()->id : 1;
            $sharedVariable->statistical_variable_id = $model->id;
            $sharedVariable->seen = true;
            $sharedVariable->type = 'OWNER';
            $sharedVariable->save();
        });
    }
}
