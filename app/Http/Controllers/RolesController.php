<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Bican\Roles\Models\Role as ModelBase;
use Bican\Roles\Models\Permission as PermissionBase;
use Illuminate\Support\Facades\Input;

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

    public function permissions($role)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($role);
        $dataPermission = $model->permissions()->get();
        $dataRole = $model->toArray();
        return ['role'=>$dataRole, 'permission'=>$dataPermission];
    }

    public function savePermissions(Request $request, $user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        $data = Input::All();
        $model->name = $data['name'];
        $model->slug = $data['slug'];
        $model->description = $data['description'];
        $model->level = $data['level'];
        $model->save();
        $model->detachAllPermissions();
        if(!empty($data['permissions'])) {
            foreach ($data['permissions'] as $p) {
                $permission = PermissionBase::find($p['value']);
                $model->attachPermission($permission);
            }
        }
        return $model;
    }
}
