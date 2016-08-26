<div id="modalRole" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> Adicionar Role </h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="nameRole" required="required"
                               placeholder="Nombre">
                    </div>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Slug</label>
                        <input type="text" class="form-control" id="slugRole" required="required"
                               placeholder="Slug">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Descripcion</label>
                        <textarea class="form-control" id="descriptionRole"></textarea>
                    </div>
                    <div class="form-group ">
                        <label for="recipient-name" class="control-label">Nivel</label>
                        <input type="text" class="form-control" id="levelRole" required="required"
                               placeholder="Nivel">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('variable.cancel_variable') }}</button>
                <button id="saveRole" type="button"
                        class="btn btn-primary">Guardar Role</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->