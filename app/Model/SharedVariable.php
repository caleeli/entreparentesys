<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SharedVariable
 *
 * @property string $name
 * @property integer $parent_id
 * @property-read belongsTo statisticalVariable
 */
class SharedVariable extends Model
{
    protected $table = 'shared_variables';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'statistical_variable_id',
        'seen',
        'type',
        'folder_id',
    ];

    public function statisticalVariable()
    {
        return $this->belongsTo('App\Model\StatisticalVariable');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
