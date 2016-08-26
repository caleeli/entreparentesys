<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Bican\Roles\Models\Permission as ModelBase;


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
        $model->fill($request->json()->all());
    }

    public function destroy($permission)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($permission);
        $model->delete();
    }

    public function attach($id)
    {
        /* @var $model ModelBase */
        $user = User::find($id);
        $user->attachRole($adminRole);
        $model = ModelBase::find($permission);
        $model->delete();
    }
}
