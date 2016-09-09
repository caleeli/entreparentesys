<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Bican\Roles\Models\Permission as ModelBase;
use Illuminate\Support\Facades\Input;


class PermissionsController extends Controller
{
    public function index()
    {
        return ['data' => ModelBase::all()->toArray()];
    }

    public function store(Request $request)
    {
        return ModelBase::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'), // optional
            'level' => $request->input('level') // optional, set to 1 by default
        ]);
    }

    public function show($permission)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($permission);
        return $model->toArray();
    }

    public function update(Request $request, $permission)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($permission);
        $data = Input::All();
        $model->name = $data['name'];
        $model->slug = $data['slug'];
        $model->description = $data['description'];
        $model->save();
        return $model;
    }

    public function destroy($permission)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($permission);
        $model->delete();
    }

}
