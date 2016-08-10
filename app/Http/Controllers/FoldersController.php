<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Folder as ModelBase;

class FoldersController extends Controller
{

    public function index()
    {
        return ModelBase::all()->toArray();
    }

    public function store(Request $request, \Illuminate\Foundation\Auth\User $user)
    {
        $data = $request->json()->all();
        $data['owner_id'] = $request->user()->id;
        $model = ModelBase::create($data);
        $model->save();
    }

    public function show($reports)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($reports);
        return $model->toArray();
    }

    public function update(Request $request, $reports)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($reports);
        $model->fill($request->json()->all());
    }

    public function destroy($reports)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($reports);
        $model->delete();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFolders()
    {
        return view('folders.folder');
    }
}
