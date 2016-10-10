<div id="mdlEditPermission" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> Editar Permiso </h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="permissionId" value="">
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="namePermission" required="required"
                               placeholder="Nombre">
                    </div>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Referencia</label>
                        <input type="text" class="form-control" id="slugPermission" required="required"
                               placeholder="código">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Descripcion</label>
                        <textarea class="form-control" id="descriptionPermission"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('variable.cancel_variable') }}</button>
                <button id="updatePermission" type="button"
                        class="btn btn-primary">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->