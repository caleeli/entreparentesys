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

    <script src="{{ asset('js/zTree/folderExample.js') }}"></script>
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
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('pivot.title_panel_folders') }}</div>

                    <div class="panel-body" style="overflow: auto;">
                        <div class="zTreeDemoBackground left">
                            <ul id="treeDemo" class="ztree"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

