<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Bican\Roles\Models\Role as ModelBase;

class RolesController extends Controller
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

    public function show($role)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($role);
        return $model->toArray();
    }

    public function update(Request $request, $role)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($role);
        $model->fill($request->json()->all());
    }

    public function destroy($role)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($role);
        $model->delete();
    }
}
