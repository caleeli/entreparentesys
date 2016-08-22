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

    @include('variables.modal_variable')
    @include('folders.modal_folder')
    @include('variables.shared_variable')
