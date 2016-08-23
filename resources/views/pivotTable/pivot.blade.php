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

<div class="container">
    <div class="row">
        <div class="col-md-12 nopadding">
            <div class="panel panel-default">

                <div class="panel-body" style="overflow: auto;">
                    <div class="row">
                        <div class="col-md-3 nopadding">
                            <ul class="nav nav-tabs">
                                <li><a href="#tab-reportes" onclick="return pivotChangeTab(this)">Reportes</a></li>
                                <li class="active"><a href="#tab-variables" onclick="return pivotChangeTab(this)">Variables</a></li>
                            </ul>
                            <div class="tab-content" id="tab-reportes" style="display:none">
                                <!------------------------------------------------------------------------->
                                @include('reports.folder_partial')
                                <!------------------------------------------------------------------------->
                            </div>
                            <div class="tab-content" id="tab-variables">
                                <!------------------------------------------------------------------------->
                                @include('folders.folder_partial')
                                <!------------------------------------------------------------------------->
                            </div>
                        </div>
                        <div class="col-md-8 nopadding">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-grafico" onclick="return pivotChangeTab(this)">Gráfico</a></li>
                                <li><a href="#tab-tabla" onclick="return pivotChangeTab(this)">Tabla</a></li>
                            </ul>
                            <div class="tab-content" id="tab-grafico" style="overflow:auto;">
                                <!------------------------------------------------------------------------->
                                <div id="output" style="margin: 30px;"></div>
                                <!------------------------------------------------------------------------->
                            </div>
                            <div class="tab-content" id="tab-tabla" style="display:none">
                                <!------------------------------------------------------------------------->
                                <!------------------------------------------------------------------------->
                            </div>
                        </div>
                        <div class="col-md-1 nopadding">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-hechos" onclick="return pivotChangeTab(this)">Hechos</a></li>
                            </ul>
                            <div class="tab-content" id="tab-hechos">
                                <!------------------------------------------------------------------------->
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

