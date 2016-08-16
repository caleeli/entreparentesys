<?php

namespace App\Http\Controllers;

use App\Model\Folder;
use App\Model\SharedVariable;
use App\Model\StatisticalVariable;
use Illuminate\Http\Request;
use App\Model\Folder as ModelBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VariablesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $model = StatisticalVariable::find($reports);
        return $model->toArray();
    }

    public function store(Request $request)
    {
        $data['name'] = $request->input('name');
        $data['parent_id'] = in_array($request->input('parent_id'), [80000000001, 90000000001, 100000000001]) ? 0 : $request->input('parent_id');
        $data['owner_id'] = $request->user()->id;
        return StatisticalVariable::create($data);
    }

    public function show($reports)
    {
        $model = StatisticalVariable::find($reports);
        return $model->toArray();
    }

    /**
     * Update statistical variable
     * @param $variableId
     */
    public function update($variableId)
    {
        $data = Input::All();
        $variable = StatisticalVariable::find($variableId);
        $variable->name = $data['name'];
        if (!empty($data['description'])) {
            $variable->description = $data['description'];
        }
        $variable->save();
    }

    public function destroy($variableId)
    {
        $model = StatisticalVariable::where('id', $variableId);
        return $model->delete();
    }
}
