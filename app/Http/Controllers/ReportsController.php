<?php

namespace App\Http\Controllers;

use App\Model\SharedReport;
use Illuminate\Http\Request;
use App\Model\Report as ModelBase;
use Illuminate\Support\Facades\Auth;
use App\Model\Folder as ModelFolder;
use Illuminate\Support\Facades\DB;

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

    public function treeReports()
    {
        $nodeMyFolders = [
            'id' => 'my-001',
            'name' => trans('reports.title_my_folder'),
            'pId' => '0',
            'open' => true,
            'isParent' => true
        ];
        $nodeSharedFolders = [
            'id' => 'sha-001',
            'name' => trans('reports.title_shared_folders'),
            'pId' => '0',
            'open' => true,
            'isParent' => true
        ];
        $nodePublicFolders = [
            'id' => 'pub-001',
            'name' => trans('reports.title_public_folders'),
            'pId' => '0',
            'open' => true,
            'isParent' => true
        ];
        $response = [];
        array_push($response, $nodeMyFolders);
        array_push($response, $nodeSharedFolders);
        array_push($response, $nodePublicFolders);

        $user = Auth::user();
        $arrayFolders = ModelFolder::where('owner_id', '=', $user->id)->orderBy('name', 'asc')->get()->toArray();
        foreach ($arrayFolders as $index => $folder) {
            $tmpFolder = array();
            $tmpFolder['id'] = 'my-' . $folder['id'];
            $tmpFolder['name'] = $folder['name'];
            if ($folder['parent_id'] == 0) {
                $tmpFolder['pId'] = $nodeMyFolders['id'];
            } else {
                $tmpFolder['pId'] = 'my-' . $folder['parent_id'];
            }
            $tmpFolder['isParent'] = true;
            $response[] = $tmpFolder;
            $arrayReports = ModelBase::where([
                ['owner_id', '=', $user->id],
                ['folder_id', '=', $folder['id']],
            ])->get()->toArray();
            foreach ($arrayReports as $ir => $report) {
                $tmpReport = array();
                $tmpReport['id'] = $folder['id'] . '-' . $report['id'];
                $tmpReport['name'] = $report['name'];
                $tmpReport['pId'] = 'my-' . $folder['id'];
                $response[] = $tmpReport;
            }
        }
        //Find shared Reports
        $shared = SharedReport::where('type', 'SHARED')
            ->where('user_id', Auth::user()->id)
            ->get()->toArray();
        foreach ($shared as $report) {
            $dataReport = ModelBase::where('id', $report['report_id'])->get()->toArray();
            $reportShared = [
                'id' => $report['report_id'] . '',
                'name' => $dataReport[0]['name'],
                'pId' => $nodeSharedFolders['id'],
            ];
            array_push($response, $reportShared);
        }
        //Find public Reports
        $public = SharedReport::where('type', 'PUBLIC')
            ->where('user_id', Auth::user()->id)
            ->get()->toArray();
        foreach ($public as $report) {
            $dataReport = ModelBase::where('id', $report['report_id'])->get()->toArray();
            $reportPublic = [
                'id' => $report['report_id'] . '',
                'name' => $dataReport[0]['name'],
                'pId' => $nodePublicFolders['id'],
            ];
            array_push($response, $reportPublic);
        }
        return $response;
    }
}