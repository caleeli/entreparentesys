@extends('layouts.app')

@push('css-head')
<!-- pivot table CSS -->
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<!-- /pivot table CSS-->
@endpush

@push('script-head')
<script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
<script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var tableUsers;
    var tableRoles;
    var tablePermissions;
    $(document).ready(function () {
        loadUsers();
//        loadRoles();
//        loadPermissions();
        $("#addRole").click(function(){
            $('#modalRole').modal('show');
            $('#saveRole').click(function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    url: "api/v1/roles",
                    dataType: "json",
                    type: "POST",
                    data: {
                        name: $('#nameRole').val(),
                        slug: $('#slugRole').val(),
                        description: $('#descriptionRole').val(),
                        level: $('#levelRole').val()
                    },
                    success: function () {
                        $('#nameRole').val('');
                        $('#slugRole').val('');
                        $('#descriptionRole').val('');
                        $('#levelRole').val('');
                        $('#modalRole').modal('hide');
                        loadRoles();
                    }
                });
            });
        });
        $("#addPermissions").click(function(){
            $('#modalPermissions').modal('show');
            $('#savePermissions').click(function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    url: "api/v1/permissions",
                    dataType: "json",
                    type: "POST",
                    data: {
                        name: $('#namePermission').val(),
                        slug: $('#slugPermission').val(),
                        description: $('#descriptionPermission').val(),
                    },
                    success: function () {
                        $('#namePermission').val('');
                        $('#slugPermission').val('');
                        $('#descriptionPermission').val('');
                        $('#modalPermissions').modal('hide');
                        loadPermissions();
                    }
                });
            });
        });
    });

    var changeTab = function(a) {
        $(a).parent().parent().parent().find('.tab-content').hide();
        $(a).parent().parent().find('li').removeClass("active");
        $(a).parent().addClass("active");
        switch ($(a).attr("href")){
            case '#tab-usuarios':
                loadUsers();
                break;
            case '#tab-roles':
                loadRoles();
                break;
            case '#tab-permisos':
                loadPermissions();
                break;
        }

        $($(a).attr("href")).show();
        return false;
    };

    var loadUsers = function () {
        tableUsers = $('#tableUsers').DataTable({
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                url: "/api/v1/user",
                dataType: "json",
                type: "GET"
            },
            destroy: true,
            columns: [
                {data: "id", title:"Id"},
                {data: "name", title:"Nombre"},
                {data: "email", title:"Correo"},
                {data: "created_at", title:"Fecha de Creacion"},
                {data: "updated_at", title:"Fecha de Modificacion"},
                {
                    data: null,
                    sortable: false,
                    title: "Opciones",
                    render: function ( data, type, row ) {
                        return '<div class="btn-group btn-group-xs" role="group" aria-label="...">'+
                                '<button type="button" class="btn btn-default">Left</button>'+
                                '<button type="button" class="btn btn-default">Middle</button>'+
                                '<button type="button" class="btn btn-default deleteUser" onclick="deleteUser(' + data.id + ');">Borrar</button>'+
                                '</div>';
                    }
                }
            ]
        });

        $('#tableUsers tbody').on('click', 'button.deleteUser', function () {
            tableUsers.row($(this).parents('tr'))
                    .remove()
                    .draw(false);
        });
    };
    var deleteUser = function (id) {
        $.ajax({
            url: "/api/v1/user/" + id,
            method: "DELETE",
            success: function () {
            }
        });
    };
    var loadRoles = function () {
        tableRoles = $('#tableRoles').DataTable({
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                url: "api/v1/roles",
                dataType: "json",
                type: "GET"
            },
            destroy: true,
            columns: [
                {data: "id", title: "Id"},
                {data: "name", title: "Nombre"},
                {data: "slug", title: "Slug"},
                {data: "description", title: "Descripcion"},
                {data: "level", title: "Nivel"},
                {data: "created_at", title: "Fecha de Creacion"},
                {data: "updated_at", title: "Fecha de Modificacion"},
                {
                    data: null,
                    sortable: false,
                    title: "Opciones",
                    render: function (data, type, row) {
                        return '<div class="btn-group btn-group-xs" role="group" aria-label="...">' +
                                '<button type="button" class="btn btn-default" >Permisos</button>' +
                                '<button type="button" class="btn btn-default deleteRole" onclick="deleteRole(' + data.id + ');">Borrar</button>' +
                                '</div>';
                    }
                }
            ]
        });

        $('#tableRoles tbody').on('click', 'button.deleteRole', function () {
            tableRoles.row($(this).parents('tr'))
                    .remove()
                    .draw(false);
        });
    };
    var deleteRole = function (id) {
        $.ajax({
            url: "/api/v1/roles/" + id,
            method: "DELETE",
            success: function () {
            }
        });
    };

    var loadPermissions = function () {
        tablePermissions = $('#tablePermissions').DataTable({
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                url: "api/v1/permissions",
                dataType: "json",
                type: "GET"
            },
            destroy: true,
            columns: [
                {data: "id", title:"Id"},
                {data: "name", title:"Nombre"},
                {data: "slug", title:"Slug"},
                {data: "description", title:"Descripcion"},
                {data: "created_at", title:"Fecha de Creacion"},
                {data: "updated_at", title:"Fecha de Modificacion"},
                {
                    data: null,
                    sortable:false,
                    title:"Opciones",
                    render: function ( data, type, row ) {
                        return '<div class="btn-group btn-group-xs" role="group" aria-label="...">'+
                                '<button type="button" class="btn btn-default">Editar</button>'+
                                '<button type="button" class="btn btn-default deletePermissions" onclick="deletePermission(' + data.id + ');">Borrar</button>'+
                                '</div>';
                    }
                }
            ]
        });

        $('#tablePermissions tbody').on('click', 'button.deletePermissions', function () {
            tablePermissions.row($(this).parents('tr'))
                    .remove()
                    .draw(false);
        });
    };
    var deletePermission = function (id) {
        $.ajax({
            url: "/api/v1/permissions/" + id,
            method: "DELETE",
            success: function () {
            }
        });
    };
</script>
@endpush

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 nopadding">
            <div class="panel panel-default">

                <div class="panel-body" style="overflow: auto;">
                    <div class="row">
                        <div class="col-md-12 nopadding">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-usuarios" onclick="return changeTab(this)">Usuarios</a></li>
                                <li><a href="#tab-roles" onclick="return changeTab(this)">Roles</a></li>
                                <li><a href="#tab-permisos" onclick="return changeTab(this)">Permisos</a></li>
                            </ul>
                            <div class="tab-content" id="tab-usuarios">
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <div class="ibox float-e-margins">
                                            <div>
                                                <table id="tableUsers" class="table table-striped table-bordered table-hover dataTable" cellspacing="0" width="100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="tab-roles" style="display:none">
                                <p class="text-right"><button type="button" class="btn btn-primary" id="addRole">Adicionar Role</button></p>
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <div class="ibox float-e-margins">
                                            <div>
                                                <table id="tableRoles" class="table table-striped table-bordered table-hover dataTable" cellspacing="0" width="100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="tab-permisos" style="display:none">
                                <p class="text-right"><button type="button" class="btn btn-primary" id="addPermissions">Adicionar Permiso</button></p>
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <div class="ibox float-e-margins">
                                            <div>
                                                <table id="tablePermissions" class="table table-striped table-bordered table-hover dataTable" cellspacing="0" width="100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@include('setting.add_role')
@include('setting.add_permissions')
