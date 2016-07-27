<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Folder
 *
 * @property string $name
 * @property integer $parent_id
 * @property-read belongsTo $parent
 */
class Folder extends Model
{
    protected $table = 'folders';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'parent_id',
        'owner_id',
    ];
    protected $rules = [
        'name'      => 'required',
        'parent_id' => 'required',
    ];
    protected $guarded = [];

    /**
     * parent relationship
     */
    public function parent()
    {
        return $this->belongsTo('App\Model\Folder');
    }

    /**
     * owner user
     */
    public function owner()
    {
        return $this->belongsTo('App\Model\User');
    }
}
