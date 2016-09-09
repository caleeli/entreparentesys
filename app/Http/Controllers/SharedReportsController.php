<?php

namespace App\Http\Controllers;

use App\Model\SharedReport;
use App\User;
use Illuminate\Http\Request;
use App\Model\Folder as ModelBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class SharedReportsController extends Controller
{

    public function index()
    {
        $model = SharedReport::find();
        return $model->toArray();
    }

    public function store(Request $request)
    {
        $type = !empty($request->input('type')) ? $request->input('type') : 'OWNER';
        $folderId = !empty($request->input('folder_id')) ? $request->input('folder_id') : 0;
        $data = [];
        switch ($type) {
            case 'OWNER':
                $data['user_id'] = Auth::user()->id;
                $data['report_id'] = $request->input('reportId');
                break;
            case 'SHARED':
                $user = User::where('email', $request->input('email'))->first();
                $data['user_id'] = $user->id;
                $data['report_id'] = (int) $request->input('reportId');
                break;
            case 'PUBLIC':
                $data['user_id'] = null;
                $data['report_id'] = $request->input('reportId');
                break;
        }

        $data['seen'] = 1;
        $data['type'] = $type;
        //$data['folder_id'] = $folderId;
        return SharedReport::create($data);
    }

    public function show($reports)
    {
        $model = SharedReport::find($reports);
        return $model->toArray();
    }

    public function update($variableId)
    {
        $data = Input::All();
        $shared = SharedReport::find($variableId);
        $folder->name = $data['name'];
        if (!empty($data['parent_id'])) {
            $folder->parent_id = $data['parent_id'];
        }
        $folder->save();
        /* @var $model ModelBase */
        $model = SharedReport::find($reports);
        $model->fill($request->json()->all());
    }

    public function destroy($reports)
    {
        /* @var $model SharedReport */
        $model = SharedReport::find($reports);
        $model->delete();
    }

    public function getVariablesFolder($folderId, $type)
    {
        $sharedVariables = SharedVariable::where('folder_id', $folderId)
            ->where('type', $type)
            ->get();
        $data = [];
        foreach ($sharedVariables as $shared) {
            $variable = StatisticalVariable::where('id', $shared->statistical_variable_id)->get();
            array_push($data, [
                'id' => $shared->id,
                'user_id' => $shared->user_id,
                'statistical_variable_id' => $shared->statistical_variable_id,
                'seen' => $shared->seen,
                'type' => $shared->type,
                'folder_id' => $shared->folder_id,
                'name' => $variable[0]->name,
                'description' => $variable[0]->description
            ]);
        }
        return $data;
    }

    public function showEmail()
    {
        $data = Input::All();
        $emails = User::where('email', 'LIKE', '%' . $data["q"] . '%')
            ->get();
        $response = [];
        foreach ($emails as $email) {
            array_push($response, $email->email);
        }
        return $response;
    }
}
