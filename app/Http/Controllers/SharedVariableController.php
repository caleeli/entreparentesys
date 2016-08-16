<?php

namespace App\Http\Controllers;

use App\Model\SharedVariable;
use App\Model\StatisticalVariable;
use App\User;
use Illuminate\Http\Request;
use App\Model\Folder as ModelBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class SharedVariableController extends Controller
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
        $model = SharedVariable::find();
        return $model->toArray();
    }

    public function store(Request $request)
    {
        $type = !empty($request->input('type')) ? $request->input('type') : 'OWNER';
        $folderId = !empty($request->input('folder_id')) ? $request->input('folder_id') : 0;
        $data = [];
        switch ($type) {
            case 'OWNER':
                $variable = StatisticalVariable::create([
                    'type' => $type,
                    'name' => $request->input('name'),
                    'description' => $request->input('description')
                ]);
                $data['user_id'] = Auth::user()->id;
                $data['statistical_variable_id'] = $variable->id;
                break;
            case 'SHARED':
                $user = User::where('email', $request->input('email'))
                    ->get();
                if (!empty($user[0])) {
                    $data['user_id'] = $user[0]->id;
                } else {
                    //Send invitation mail
                    $var = StatisticalVariable::where('id', $request->input('variableId'))
                        ->get();

                    $data = [
                        'app_name'  => Lang::get('labels.system'),
                        'title'     => Lang::get('variable.title_variable_shared'),
                        'variable'  => $var[0]->name
                    ];
                    Mail::queue('variables.email_shared_variable', $data, function ($message) use ($request) {
                        $message->to($request->input('email'), 'Variable');
                        $message->subject(Lang::get('variable.variable_shared_subject'));
                    });
                }
                $data['statistical_variable_id'] = $request->input('variableId');
                break;
            case 'PUBLIC':
                $data['user_id'] = null;
                $data['statistical_variable_id'] = $request->input('variableId');
                break;
        }

        $data['seen'] = 1;
        $data['type'] = $type;
        $data['folder_id'] = $folderId;
        return SharedVariable::create($data);
    }

    public function show($reports)
    {
        $model = SharedVariable::find($reports);
        return $model->toArray();
    }

    public function update($variableId)
    {
        $data = Input::All();
        $shared = SharedVariable::find($variableId);
        $folder->name = $data['name'];
        if (!empty($data['parent_id'])) {
            $folder->parent_id = $data['parent_id'];
        }
        $folder->save();
        /* @var $model ModelBase */
        $model = SharedVariable::find($reports);
        $model->fill($request->json()->all());
    }

    public function destroy($reports)
    {
        /* @var $model ModelBase */
        $model = SharedVariable::find($reports);
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
