<?php

namespace App\Http\Controllers;

use Bican\Roles\Models\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as ModelBase;
use Bican\Roles\Models\Role as RoleBase;
use Bican\Roles\Models\Permission as PermissionBase;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function index()
    {
        return ['data' => ModelBase::all()->toArray()];
    }

    public function store(Request $request)
    {
        //
    }

    public function show($user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        return $model->toArray();
    }

    public function update(Request $request, $user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        $model->fill($request->json()->all());
    }

    public function destroy($user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        $model->delete();
    }

    public function rolesPermissions($user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        $dataRole = $model->getRoles();
        $dataPermission = $model->getPermissions();
        $dataUser = $model->toArray();
        return ['user'=>$dataUser, 'role'=>$dataRole, 'permission'=>$dataPermission];
    }

    public function saveRolesPermissions(Request $request, $user)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($user);
        $data = Input::All();
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->save();
        $model->detachAllRoles();
        if(!empty($data['roles'])) {
            foreach ($data['roles'] as $r) {
                $role = RoleBase::find($r['value']);
                $model->attachRole($role);
            }
        }
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
