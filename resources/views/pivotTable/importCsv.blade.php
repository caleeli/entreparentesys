@extends('layouts.app')

@push('css-head')
    <!-- pivot table CSS -->
    <link href="{{ asset('css/pivot.css') }}" rel="stylesheet" type="text/css">
    <!-- /pivot table CSS-->
@endpush

@push('script-head')
    <!-- pivot table JS-->
    <script src="{{ asset('js/pivotTable/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/pivotTable/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('js/pivotTable/d3.min.js') }}"></script>
    <script src="{{ asset('js/pivotTable/jquery.csv-0.71.min.js') }}"></script>
    <script src="{{ asset('js/pivotTable/c3.min.js') }}"></script>

    <script src="{{ asset('js/pivotTable/pivot.js') }}"></script>
    <script src="{{ asset('js/pivotTable/pivot.es.js') }}"></script>
    <script src="{{ asset('js/pivotTable/d3_renderers.js') }}"></script>
    <script src="{{ asset('js/pivotTable/c3_renderers.js') }}"></script>
    <script src="{{ asset('js/pivotTable/export_renderers.js') }}"></script>

    <script src="{{ asset('js/pivotTable/importCsv.js') }}"></script>
    <!-- /pivot table JS-->
@endpush

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('pivot.title_panel_pivot') }}</div>

                    <div class="panel-body" style="overflow: auto;">

                        <p align="center">Select a CSV file: <input id="csv" type="file" /></p>
                        <div id="output" style="margin: 10px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

