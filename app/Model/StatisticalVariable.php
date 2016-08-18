<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StatisticalVariable extends Model
{

    protected $fillable = [
        'name',
        'type',
        'description'
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

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'statistical_variables';

    /**
     * The primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'type',
        'name',
        'description'
    ];*/

    //////////////////////////////////
    //Mutators
    //////////////////////////////////

    /**
     * Set the hash for the type variable
     *
     * @param string $value
     *
     * @return void
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = $value;
    }

    /**
     * Set the hash for the name variable
     *
     * @param string $value
     *
     * @return void
     */
    public function setNameAttribute($value)
    {
        $name = trim($value);
        $name = preg_replace('/\s\s+/', ' ' , $name);
        $this->attributes['name'] = $name;
    }

    public function shares(){
        return $this->hasMany(\App\Model\SharedVariable::class);
    }
}
