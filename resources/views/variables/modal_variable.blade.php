<div id="variableModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> {{ trans('variable.title_panel_variable') }} </h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" class="form-control" id="variable_id">
                    <input type="hidden" class="form-control" id="folder_id">
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">{{ trans('variable.name') }}</label>
                        <input type="text" class="form-control" id="variable_name" required="required"
                               placeholder="{{ trans('variable.placeholder_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">{{ trans('variable.description') }}</label>
                        <textarea class="form-control" id="variable_description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('variable.cancel_variable') }}</button>
                <button id="saveVariable" type="button"
                        class="btn btn-primary">{{ trans('variable.save_variable') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->