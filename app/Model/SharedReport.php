<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SharedReport extends Model
{
    protected $table = 'shared_reports';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'report_id',
        'seen',
        'type'
    ];

    public function report()
    {
        return $this->belongsTo('App\Model\Report');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
