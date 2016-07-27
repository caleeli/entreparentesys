<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Report as ModelBase;

class ReportsController extends Controller
{

    public function index()
    {
        return ModelBase::all()->toArray();
    }

    public function store(Request $request)
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
}