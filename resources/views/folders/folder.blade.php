@extends('layouts.app')

@push('css-head')
        <!-- pivot table CSS -->
<link href="{{ asset('css/zTree/zTreeStyle.css') }}" rel="stylesheet" type="text/css">
<!-- /pivot table CSS-->
@endpush

@push('script-head')
    <!-- zTree JS-->
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/zTree/jquery.ztree.all.min.js') }}"></script>

    <script src="{{ asset('js/zTree/folderTree.js') }}"></script>
    <!-- /zTree JS-->
@endpush

@section('content')
    <style type="text/css">
        .ztree li span.button.add {
            margin-left: 2px;
            margin-right: -1px;
            background-position: -144px 0;
            vertical-align: top;
            *vertical-align: middle
        }
        .ztree li span.button.shared {
            margin-left: 2px;
            margin-right: -1px;
            background-position: -110px -80px;
            vertical-align: top;
            *vertical-align: middle
        }
        .ztree li span.button.variable {
            margin-left: 2px;
            margin-right: -1px;
            background-position: -110px -32px;
            vertical-align: top;
            *vertical-align: middle
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('folder.title_panel_folder') }}</div>

                    <div class="panel-body" style="overflow: auto;">
                        <div class="zTreeDemoBackground left">
                            <ul id="foldersTree" class="ztree"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('variables.modal_variable')
    @include('folders.modal_folder')
    @include('variables.shared_variable')
@endsection

