<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Report
 *
 * @property string $name
 * @property integer $folder_id
 * @property integer $owner_id
 * @property-read belongsTo $folder
 * @property-read belongsTo $owner
 */
class Report extends Model
{
    protected $table = 'reports';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'folder_id',
        'owner_id',
    ];
    protected $rules = [
        'name'      => 'required',
        'folder_id' => 'required',
        'owner_id'  => 'required',
    ];
    protected $guarded = [];

    /**
     * Boot
     */
    public static function boot()
    {
        static::creating(function(Report $model) {
            $model->owner_id = Auth::user() ? Auth::user()->id : 1;
        });
        static::created(function(Report $model) {
            $sharedVariable = new SharedReport();
            $sharedVariable->user_id = Auth::user() ? Auth::user()->id : 1;
            $sharedVariable->report_id = $model->id;
            $sharedVariable->seen = true;
            $sharedVariable->type = 'OWNER';
            $sharedVariable->save();
        });
    }

    /**
     * folder relationship
     */
    public function folder()
    {
        return $this->belongsTo('App\Model\Folder');
    }

    /**
     * owner relationship
     */
    public function owner()
    {
        return $this->belongsTo('App\Model\User');
    }
}
