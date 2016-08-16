<div id="sharedModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> {{ trans('variable.title_panel_shared') }} </h4>
            </div>
            <div class="modal-body">
                <form class="eventInsForm">
                    <input type="hidden" class="form-control" id="variable_id">
                    <div class="form-group ui-widget">
                        <label for="recipient-name" class="control-label">{{ trans('variable.email_shared') }}</label>
                        <input type="text" class="form-control shared-variable-complete" id="shared_name" required="required"
                               placeholder="{{ trans('variable.shared_placeholder_name') }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('variable.cancel_variable') }}</button>
                <button id="saveSharedVariable" type="button"
                        class="btn btn-primary">{{ trans('variable.save_shared_variable') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->