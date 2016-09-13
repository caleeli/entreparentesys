@extends('layouts.app')

@push('css-head')
<link href="/css/dropzone.min.css" rel="stylesheet" />
<style>
    .dropzone { margin-bottom: 3rem; }
    .dropzone {border: 2px dashed #0087F7;border-radius: 5px;background: white;}
    .dropzone .dz-message { font-weight: 400; }
    .dropzone .dz-message .note { font-size: 0.8em; font-weight: 200; display: block; margin-top: 1.4rem; }
</style>
<!-- Data Tables -->
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endpush
@section('header')
<script type="text/javascript" src="/js/dropzone.min.js"></script>
@endsection
@section('content')
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Cargar Reporte desde un Archivo Excel</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-wrench"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
            </ul>
        </div>
    </div>
    <div class="ibox-content">
        <div><h3>1. Cargar Excel<h3></div>
                    <div id="uploadFile">Importa tu archivo aquí</div>
                    <form action="{{route('import-excel.update',0)}}" method="PUT">
                        <div><h3>2. Analizar Variables y Dimensiones <img id="step2-loading" style="display:none" src="/img/loading.gif" height="24"></h3></div>
                        <input id="filename" name="filename" type="hidden" />
                        <div class="form-group">
                            <label>{{trans('labels.report_name')}}</label>
                            <input class="form-control" type="text" id="report_name" name="report_name" />
                        </div>
                        <div class="form-group">
                            <label>{{trans('labels.variables')}}</label>
                            <div class="ibox float-e-margins">
                                <div>
                                    <table id="variables" class="table table-striped table-bordered table-hover dataTable" cellspacing="0" width="100%">
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{trans('labels.dimensions')}}</label>
                            <div class="ibox float-e-margins">
                                <div>
                                    <table id="dimensions" class="table table-striped table-bordered table-hover dataTable" cellspacing="0" width="100%">
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button id="finish" type="button" class="btn btn-white" type="submit" ><h3>3. {{trans('labels.finish_import')}} <img id="step3-loading" style="display:none" src="/img/loading.gif" height="24"></h3></button>
                    </form>
                    </div>
                    </div>
<div id="finishImport" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> Importación exitosa </h4>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" onclick="location.href='/home'">Continuar hacia la pantalla de inicio</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="failImport" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> Ocurrio un error durante la importación </h4>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('folder.cancel_folder') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
                    @endsection
                    @push('script-head')
                    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
                    <script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
                    <script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
                    <script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
                    <script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
                    <script>
$(function () {
    var variablesDT, dimensionsDT;
    var myDropzone = new Dropzone("#uploadFile", {
        url: "{{route('import-excel.store')}}",
        uploadMultiple: false,
        init: function () {
            $("#uploadFile").addClass('dropzone');
            this.on('uploadprogress', function (file, progress) {
                if (progress == 100) {
                    $("#step2-loading").show();
                }
            });
            this.on('success', function (file, response, progressEvent) {
                var $previews = $("#uploadFile .dz-preview");
                $previews.each(function (index, div) {
                    if (index < ($previews.length - 1)) {
                        $(div).remove();
                    }
                });
                $("#step2-loading").hide();
                $("#report_name").val(response.reportName);
                $("#filename").val(response.filename);
                if(variablesDT) {
                    variablesDT.destroy();
                }
                variablesDT=$('#variables').DataTable({
                    data: response.variables,
                    columns: [
                        {data: "name", title: "{{trans('variables.name')}}"},
                        {data: "type", title: "{{trans('variables.type')}}"},
                    ]
                });
                if(dimensionsDT) {
                    dimensionsDT.destroy();
                }
                dimensionsDT=$('#dimensions').DataTable({
                    data: response.dimensions,
                    columns: [
                        {data: "name", title: "{{trans('variables.excel')}}"},
                        {data: "name", title: "{{trans('variables.column')}}",
                            render: {
                                display: function (value, display, object, cell) {
                                    return numberToLetters(cell.row + 3);
                                }
                            }
                        },
                        {data: "name", title: "{{trans('variables.dimension')}}"},
                        {data: 'name', title: "{{trans('variables.associated_values')}}",
                            render: {
                                display: function (dimension) {
                                    var array = [], object = response.associatedValues[dimension];
                                    return array_values(response.associatedValues[dimension], 'value').join(',');
                                }
                            }
                        }
                    ]
                });
            });
            this.on('error', function () {
                $previews.remove();
            });
        }
    });
    $("#finish").click(function () {
        $("#step3-loading").show();
        $.ajax({
            url: "/import-excel/1",
            method: "PUT",
            data: {
                filename: $("#filename").val(),
                report_name: $("#report_name").val(),
            },
            success: function () {
                $("#step3-loading").hide();
                $("#finishImport").modal('show');
            },
            failure: function(){
                $("#step3-loading").hide();
                $("#failImport").modal('show');
            }
        });
    });
})
function array_values(object, name) {
    var array = [];
    for (var a in object) {
        if (typeof object[a] !== 'function') {
            array.push(object[a][name]);
        }
    }
    return array;
}
function numberToLetters(nNum) {
    var result;
    if (nNum <= 26) {
        result = letter(nNum);
    } else {
        var modulo = nNum % 26;
        var quotient = Math.floor(nNum / 26);
        if (modulo === 0) {
            result = letter(quotient - 1) + letter(26);
        } else {
            result = letter(quotient) + letter(modulo);
        }
    }

    return result;
}

function letter(nNum) {
    var a = "A".charCodeAt(0);
    return String.fromCharCode(a + nNum - 1);
}

                    </script>
                    @endpush