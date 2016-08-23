<?php

namespace App\Http\Controllers;

use App\Model\Folder;
use App\Model\SharedVariable;
use App\Model\StatisticalVariable;
use Illuminate\Http\Request;
use App\Model\Folder as ModelBase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FoldersController extends Controller
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
        $folders = ModelBase::where('owner_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
        $nodeMyFolders = [
            'id' => 'my-001',
            'name' => trans('folder.title_my_folder'),
            'parent_id' => 0,
            'owner_id' => Auth::user()->id,
            'folder' => true
        ];
        $nodeSharedFolders = [
            'id' => 'sha-001',
            'name' => trans('folder.title_shared_folders'),
            'parent_id' => 0,
            'owner_id' => Auth::user()->id,
            'folder' => true
        ];
        $nodePublicFolders = [
            'id' => 'pub-001',
            'name' => trans('folder.title_public_folders'),
            'parent_id' => 0,
            'owner_id' => Auth::user()->id,
            'folder' => true
        ];
        $data = [];
        array_push($data, $nodeMyFolders);
        array_push($data, $nodeSharedFolders);
        array_push($data, $nodePublicFolders);
        $sharedController = new SharedVariableController();
        foreach ($folders->toArray() as $key => $value) {
            $dataVariables = $sharedController->getVariablesFolder($value['id'], 'OWNER');

            $value['id'] = 'my-' . $value['id'];
            if ($value['parent_id'] == 0) {
                $value['parent_id'] = $nodeMyFolders['id'];
            } else {
                $value['parent_id'] = 'my-' . $value['parent_id'];
            }
            $value['folder'] = true;
            array_push($data, $value);

            foreach ($dataVariables as $variables) {
                array_push($data, [
                    'id' => 'var-' . $variables['statistical_variable_id'],
                    'parent_id' => $value['id'],
                    'folder' => false,
                    'name' => $variables['name']
                ]);
            }
        }
        //Find shared variables
        $sharedVariables = SharedVariable::where('type', 'SHARED')
            ->where('user_id', Auth::user()->id)
            ->get();
        foreach ($sharedVariables as $variable) {
            $id = 'sha-' . $variable->id;
            $statisticalVariable = StatisticalVariable::where('id', $variable->statistical_variable_id)->get();
            $variableShared = [
                'id' => $id,
                'name' => $statisticalVariable[0]->name,
                'parent_id' => $nodeSharedFolders['id'],
                'owner_id' => $variable->user_id,
                'folder' => false
            ];
            array_push($data, $variableShared);
        }


        //Find public variables
        $publicVariables = SharedVariable::where('type', 'PUBLIC')
            ->get();
        foreach ($publicVariables as $variable) {
            $id = 'pub-' . $variable->id;
            $statisticalVariable = StatisticalVariable::where('id', $variable->statistical_variable_id)->get();
            $variablePublic = [
                'id' => $id,
                'name' => $statisticalVariable[0]->name,
                'parent_id' => $nodePublicFolders['id'],
                'owner_id' => $variable->user_id,
                'folder' => false
            ];
            array_push($data, $variablePublic);
        }

        return $data;
    }

    public function store(Request $request)
    {
        $data['name'] = $request->input('name');

        $data['parent_id'] = $request->input('parent_id') == 0 ? null : $request->input('parent_id');
        $data['owner_id'] = $request->user()->id;
        return Folder::create($data);
    }

    public function show($reports)
    {
        /* @var $model ModelBase */
        $model = ModelBase::find($reports);
        return $model->toArray();
    }

    public function update($id)
    {
        $data = Input::All();
        $folder = Folder::find($id);
        $folder->name = $data['name'];
        if (!empty($data['parent_id'])) {
            $folder->parent_id = $data['parent_id'];
        }
        $folder->save();
    }

    private function deleteParentFolder ($folderId)
    {
        $folders = Folder::where('parent_id', $folderId)->get();
        foreach ($folders as $folder) {
            $this->deleteParentFolder($folder->id);
            $folder->delete();
        }
    }

    public function destroy($folderId)
    {
        $this->deleteParentFolder($folderId);
        $model = Folder::where('id', $folderId);
        return $model->delete();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFolders()
    {
        return view('folders.folder');
    }
}
