@extends('layouts.app')

@push('css-head')
<!-- pivot table CSS -->
<link href="{{ asset('css/pivot.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/zTree/zTreeStyle.css') }}" rel="stylesheet" type="text/css">
<!-- pivot table CSS-->
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
<script src="{{ asset('js/zTree/jquery.ztree.excheck.min.js') }}"></script>
<script src="{{ asset('js/zTree/jquery.ztree.exedit.min.js') }}"></script>

<script src="{{ asset('js/pivotTable/uds.js') }}"></script>
<script src="{{ asset('js/pivotTable/corechart.js') }}"></script>
<script src="{{ asset('js/zTree/variablesTree.js') }}"></script>
<script src="{{ asset('js/zTree/folderTree.js') }}"></script>

<script src="{{ asset('js/zTree/treeReports.js') }}"></script>
<!-- /zTree JS-->
<script>
function pivotChangeTab(a) {
    $(a).parent().parent().parent().find('.tab-content').hide();
    $(a).parent().parent().find('li').removeClass("active");
    $(a).parent().addClass("active");
    $($(a).attr("href")).show();
    return false;
}
</script>
@endpush

@section('content')
<style>
#outputGrafico {
    transform: scale(0.6) translate(-35%,-30%);
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 nopadding">
            <div class="panel panel-default">

                <div class="panel-body" style="overflow: auto;">
                    <div class="row">
                        <div class="col-md-4 nopadding">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-reportes" onclick="return pivotChangeTab(this)">Reportes</a></li>
                                <li><a href="#tab-variables" onclick="return pivotChangeTab(this)">Variables</a></li>
                                <li><a href="#tab-hechos" onclick="return pivotChangeTab(this)">Hechos</a></li>
                            </ul>
                            <div class="tab-content" id="tab-reportes">
                                <!------------------------------------------------------------------------->
                                @include('reports.folder_partial')
                                <!------------------------------------------------------------------------->
                            </div>
                            <div class="tab-content" id="tab-variables" style="display:none">
                                <!------------------------------------------------------------------------->
                                @include('folders.folder_partial')
                                <!------------------------------------------------------------------------->
                            </div>
                        </div>
                        <div class="col-md-8 nopadding">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-grafico" onclick="return pivotChangeTab(this)">Gráfico</a></li>
                            </ul>
                            <div class="tab-content" id="tab-grafico" style="overflow:auto;">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Table').change();"><img src="/img/icons/table.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Table Barchart').change();"><img src="/img/icons/table_chart.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Heatmap').change();"><img src="/img/icons/table_heatmap.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Row Heatmap').change();"><img src="/img/icons/table_heatmap_row.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Col Heatmap').change();"><img src="/img/icons/table_heatmap_col.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Line Chart').change();"><img src="/img/icons/chart_line.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Bar Chart').change();"><img src="/img/icons/chart_bar.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Stacked Bar Chart').change();"><img src="/img/icons/chart_stacked.png"></button>
                                  <button type="button" class="btn btn-default" onclick="$('.pvtRenderer').val('Area Chart').change();"><img src="/img/icons/chart_area.png"></button>
                                  <span>
                                    <div id="selectAgregator" style="font-size: 50%;">
                                    </div>
                                  </span>
                                </div>
                                <!------------------------------------------------------------------------->
                                <div id="outputGrafico" style="margin: 10px;"></div>
                                <!------------------------------------------------------------------------->
                            </div>
                            <div class="tab-content" id="tab-tabla" style="display:none">
                                <!------------------------------------------------------------------------->
                                <div id="outputTabla" style="margin: 10px;"></div>
                                <!------------------------------------------------------------------------->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@include('reports.shared_report')