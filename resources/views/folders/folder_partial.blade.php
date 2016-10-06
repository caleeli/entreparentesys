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

                        <div class="zTreeDemoBackground left">
                            <ul id="foldersTree" class="ztree"></ul>
                        </div>
                        <div class="zTreeDemoBackground left" id="variablesPivotRender" style="display: none; margin-left: 10px;">
                            <div style="display:none;">
                            <label for="selectRender">Graficos</label>
                            <div id="selectRender">
                            </div>

                            </div>
                            <label for="variablesTree">Variables</label>
                            <ul id="variablesTree" class="ztree"></ul>
                            <br>
                            <button type="button" class="btn btn-default" style="margin-left: 40px;"  onclick="return generateVariables()">Generar Grafico</button>
                        </div>

    @include('variables.modal_variable')
    @include('folders.modal_folder')
    @include('variables.shared_variable')
