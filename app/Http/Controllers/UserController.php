<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as ModelBase;

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

}
