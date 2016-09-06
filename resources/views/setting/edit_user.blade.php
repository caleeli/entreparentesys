<div id="mdlEditUser" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> Editar Usuario </h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="userId" value="">
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="nameUser" required="required"
                               placeholder="Nombre">
                    </div>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Correo</label>
                        <input type="text" class="form-control" id="emailUser" required="required"
                               placeholder="Slug">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Roles</div>
                                <div class="panel-body" id="rolesUser"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Permisos</div>
                                <div class="panel-body" id="permissionUser"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('variable.cancel_variable') }}</button>
                <button id="updateUser" type="button"
                        class="btn btn-primary">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->