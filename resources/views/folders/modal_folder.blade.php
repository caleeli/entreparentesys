<div id="folderModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> {{ trans('folder.title_panel_folder') }} </h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" class="form-control" id="update_folder_id">
                    <input type="hidden" class="form-control" id="folder_id">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">{{ trans('folder.name') }}</label>
                        <input type="text" class="form-control" id="folder_name"
                               placeholder="{{ trans('folder.placeholder_name') }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('folder.cancel_folder') }}</button>
                <button id="saveFolder" type="button"
                        class="btn btn-primary">{{ trans('folder.save_folder') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->