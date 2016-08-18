@extends('layouts.app')

@push('css-head')
    <!-- pivot table CSS -->
    <link href="{{ asset('css/pivot.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/zTree/zTreeStyle.css') }}" rel="stylesheet" type="text/css">
    <!-- /pivot table CSS-->
@endpush

@push('script-head')
    <!-- pivot table JS-->
    <script src="{{ asset('js/pivotTable/jsapi') }}"></script>

    <script src="{{ asset('js/pivotTable/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/pivotTable/jquery.ui.touch-punch.min.js') }}"></script>

    <script src="{{ asset('js/pivotTable/pivot.js') }}"></script>
    <script src="{{ asset('js/pivotTable/pivot.es.js') }}"></script>
    <script src="{{ asset('js/pivotTable/gchart_renderers.js') }}"></script>

    <script src="{{ asset('js/pivotTable/pivotExample.js') }}"></script>
    <!-- /pivot table JS-->

    <!-- zTree JS-->
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/zTree/jquery.ztree.all.min.js') }}"></script>

    <script src="{{ asset('js/zTree/folderTree.js') }}"></script>
    <!-- /zTree JS-->
@endpush

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 nopadding">
                <!------------------------------------------------------------------------->
                @include('folders.folder_partial')
                <!------------------------------------------------------------------------->
            </div>
            <div class="col-md-8 nopadding">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('pivot.title_panel_pivot') }}</div>

                    <div class="panel-body" style="overflow: auto;">
                        <div id="output" style="margin: 30px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

