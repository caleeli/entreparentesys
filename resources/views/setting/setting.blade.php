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
        $('#updateUser').click(function () {
            var userId = $("#mdlEditUser #userId").val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url: "/api/v1/user/"+userId+"/role-permission",
                dataType: "json",
                type: "POST",
                data: {
                    name: $('#mdlEditUser #nameUser').val(),
                    email: $('#mdlEditUser #emailUser').val(),
                    roles: $('#mdlEditUser .rolesUser:checked').serializeArray(),
                    permissions: $('#mdlEditUser .permissionUser:checked').serializeArray()
                },
                success: function () {
                    $('#mdlEditUser').modal('hide');
                }
            });
        });
        $('#updateRole').click(function () {
            var roleId = $("#mdlEditRole #roleId").val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url: "/api/v1/roles/"+roleId+"/permission",
                dataType: "json",
                type: "POST",
                data: {
                    name: $('#mdlEditRole #nameRole').val(),
                    slug: $('#mdlEditRole #slugRole').val(),
                    description: $('#mdlEditRole #descriptionRole').val(),
                    level: $('#mdlEditRole #levelRole').val(),
                    permissions: $('#mdlEditRole .permissionUser:checked').serializeArray()
                },
                success: function () {
                    $('#mdlEditRole').modal('hide');
                }
            });
        });
        $('#updatePermission').click(function () {
            var permissionId = $("#mdlEditPermission #permissionId").val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url: "/api/v1/permissions/"+permissionId,
                dataType: "json",
                type: "PUT",
                data: {
                    name: $('#mdlEditPermission #namePermission').val(),
                    slug: $('#mdlEditPermission #slugPermission').val(),
                    description: $('#mdlEditPermission #descriptionPermission').val(),
                },
                success: function () {
                    $('#mdlEditPermission').modal('hide');
                }
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
                                '<button type="button" class="btn btn-info btnEditUser" data-id="' + data.id + '">Editar</button>'+
                                '<button type="button" class="btn btn-danger deleteUser" onclick="deleteUser(' + data.id + ');">Borrar</button>'+
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
        $('#tableUsers tbody').off("click", "button.btnEditUser");
        $('#tableUsers tbody').on("click", "button.btnEditUser", function () {
            var userId = $(this).data('id');
            var promise = $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url : "/api/v1/roles",
                type : "GET",
                success: function(data2){
                    $("#mdlEditUser #rolesUser").html('');
                    $.each(data2.data, function (index, role) {
                        $("#mdlEditUser #rolesUser").append('<div class="checkbox">'+
                                '<label>'+
                                '<input type="checkbox" class="rolesUser" name="rolesUser[]" name="rolesUser" value="'+role.id+'">'+role.name+'</label>'+
                                '</div>'
                        );
                    });
                },
                error : function(xhr,errmsg,err) {
                    console.log(xhr.status + ": " + xhr.responseText);
                }
            });
            promise.then(function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                    url : "/api/v1/permissions",
                    type : "GET",
                    success: function(data2){
                        $("#mdlEditUser #permissionUser").html('');
                        $.each(data2.data, function (index, permission) {
                            $("#mdlEditUser #permissionUser").append('<div class="checkbox">'+
                                    '<label>'+
                                    '<input type="checkbox" class="permissionUser" name="permissionUser[]" value="'+permission.id+'">'+permission.name+'</label>'+
                                    '</div>'
                            );
                            //return (value !== 'three');
                        });
                    },
                    error : function(xhr,errmsg,err) {
                        console.log(xhr.status + ": " + xhr.responseText);
                    }
                });
            });
            promise.then(function(){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                    url: "/api/v1/user/"+userId+"/role-permission",
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
                        console.log(data);
                        $("#mdlEditUser #userId").val(userId);
                        $("#mdlEditUser .modal-body #nameUser").val(data.user.name);
                        $("#mdlEditUser .modal-body #emailUser").val(data.user.email);
                        $.each(data.role, function (index, role) {
                            $("#mdlEditUser #rolesUser input[type='checkbox'][value='"+role.id+"']").attr('checked', true);
                        });
                        $.each(data.permission, function (index, permission) {
                            $("#mdlEditUser #permissionUser input[type='checkbox'][value='"+permission.id+"']").attr('checked', true);
                        });
                    }
                });
            });
            $('#mdlEditUser').modal('show');
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
                {data: "name", title: "Rol"},
                {data: "slug", title: "Código"},
                {data: "description", title: "Descripción"},
                {data: "level", title: "Nivel"},
                {data: "created_at", title: "Fecha de Creacion"},
                {data: "updated_at", title: "Fecha de Modificacion"},
                {
                    data: null,
                    sortable: false,
                    title: "Opciones",
                    render: function (data, type, row) {
                        return '<div class="btn-group btn-group-xs" role="group" aria-label="...">' +
                                '<button type="button" class="btn btn-info btnEditRole" data-id="' + data.id + '">Editar</button>' +
                                '<button type="button" class="btn btn-danger deleteRole" onclick="deleteRole(' + data.id + ');">Borrar</button>' +
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
        $('#tableRoles tbody').off("click", "button.btnEditRole");
        $('#tableRoles tbody').on("click", "button.btnEditRole", function () {
            var roleId = $(this).data('id');
            var promise = $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url : "/api/v1/permissions",
                type : "GET",
                success: function(data2){
                    $("#mdlEditRole #permissionUser").html('');
                    $.each(data2.data, function (index, permission) {
                        $("#mdlEditRole #permissionUser").append('<div class="checkbox">'+
                                '<label>'+
                                '<input type="checkbox" class="permissionUser" name="permissionUser[]" value="'+permission.id+'">'+permission.name+'</label>'+
                                '</div>'
                        );
                    });
                },
                error : function(xhr,errmsg,err) {
                    console.log(xhr.status + ": " + xhr.responseText);
                }
            });
            promise.then(function(){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                    url: "/api/v1/roles/"+roleId+"/permission",
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
                        console.log(data);
                        $("#mdlEditRole #roleId").val(roleId);
                        $("#mdlEditRole .modal-body #nameRole").val(data.role.name);
                        $("#mdlEditRole .modal-body #slugRole").val(data.role.slug);
                        $("#mdlEditRole .modal-body #descriptionRole").val(data.role.description);
                        $("#mdlEditRole .modal-body #levelRole").val(data.role.level);
                        $.each(data.permission, function (index, permission) {
                            $("#mdlEditRole #permissionUser input[type='checkbox'][value='"+permission.id+"']").attr('checked', true);
                        });
                    }
                });
            });
            $('#mdlEditRole').modal('show');
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
                {data: "slug", title:"Código"},
                {data: "description", title:"Descripción"},
                {data: "created_at", title:"Fecha de Creacion"},
                {data: "updated_at", title:"Fecha de Modificacion"},
                {
                    data: null,
                    sortable:false,
                    title:"Opciones",
                    render: function ( data, type, row ) {
                        return '<div class="btn-group btn-group-xs" role="group" aria-label="...">'+
                                '<button type="button" class="btn btn-info btnEditPermission" data-id="' + data.id + '">Editar</button>'+
                                '<button type="button" class="btn btn-danger deletePermissions" onclick="deletePermission(' + data.id + ');">Borrar</button>'+
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
        $('#tablePermissions tbody').off("click", "button.btnEditPermission");
        $('#tablePermissions tbody').on("click", "button.btnEditPermission", function () {
            var permissionId = $(this).data('id');
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                url: "/api/v1/permissions/"+permissionId,
                dataType: "json",
                type: "GET",
                success: function (data) {
                    $("#mdlEditPermission #permissionId").val(permissionId);
                    $("#mdlEditPermission .modal-body #namePermission").val(data.name);
                    $("#mdlEditPermission .modal-body #slugPermission").val(data.slug);
                    $("#mdlEditPermission .modal-body #descriptionPermission").val(data.description);
                }
            });
            $('#mdlEditPermission').modal('show');
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
                                <p class="text-right"><button type="button" class="btn btn-primary" id="addRole">Adicionar Rol</button></p>
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
@include('setting.edit_user')
@include('setting.edit_role')
@include('setting.edit_permission')
