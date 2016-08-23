var log, className = "dark";
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

var removeFolder = function (treeNode) {
    if (folderUID.indexOf(treeNode.id) != -1) {
        folderId = 0;
    } else {
        folderId = treeNode.id.split('-');
        folderId = folderId[1];
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: "/api/v1/folders/" + folderId,
        type: "DELETE",
        success: function (data) {
            $('#folder_id').val(0);
            $('#folder_name').val('');

            showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
        }
    });
};

var removeVariable = function (treeNode) {
    if (folderUID.indexOf(treeNode.id) != -1) {
        folderId = 0;
    } else {
        folderId = treeNode.id.split('-');
        folderId = folderId[1];
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: "/api/v1/variable/" + folderId,
        //dataType: "json",
        type: "DELETE",
        success: function (data) {
            //change
            showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
            loadTreeReports();
        }
    });
};

var updateFolder = function (treeNode, isCancel) {
    if (folderUID.indexOf(treeNode.id) != -1) {
        folderId = 0;
    } else {
        folderId = treeNode.id.split('-');
        folderId = folderId[1];
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: "/api/v1/folders/" + folderId,
        dataType: "json",
        type: "PUT",
        data: {
            name: treeNode.name
        },
        success: function (data) {
            $('#folder_id').val(0);
            $('#folder_name').val('');
            showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
        }
    });
};

var updateVariable = function (treeNode, isCancel) {
    if (folderUID.indexOf(treeNode.id) != -1) {
        folderId = 0;
    } else {
        folderId = treeNode.id.split('-');
        folderId = folderId[1];
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/api/v1/variable/" + folderId,
        dataType: "json",
        type: "PUT",
        data: {
            name: treeNode.name
        },
        success: function (data) {
            $('#folder_id').val(0);
            $('#folder_name').val('');
            showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
        }
    });
};

var showLog = function (str) {
    if (!log) log = $("#log");
    log.append("<li class='" + className + "'>" + str + "</li>");
    if (log.children("li").length > 8) {
        log.get(0).removeChild(log.children("li")[0]);
    }
};

var getTime = function () {
    var now = new Date(),
        h = now.getHours(),
        m = now.getMinutes(),
        s = now.getSeconds(),
        ms = now.getMilliseconds();
    return (h + ":" + m + ":" + s + " " + ms);
};

var folderUID = ['my-001', 'sha-001', 'pub-001'];




var saveVariable = function (tree, treeNode, variableName, variableDescription, parentId) {
    if (folderUID.indexOf(parentId) != -1) {
        folderId = 0;
    } else {
        parent = parentId.split('-');
        folderId = parent[1];
    }
    $.ajax({
        url: "/api/v1/sharedVariable",
        dataType: "json",
        type: "POST",
        data: {
            name: variableName,
            type: 'OWNER',
            description: variableDescription,
            folder_id: folderId
        },
        success: function (data) {
            $('#folder_id').val(0);
            $('#variable_name').val('');
            $('#variable_description').text('');
            tree.addNodes(treeNode, {
                id:  'var-' + data.statistical_variable_id,
                pId: parent,
                isParent: false,
                name: variableName
            });
            $('#variableModal').modal('hide');
        }
    });
};

var newCount = 1;
var newFolder = 1;
var newVariable = 1;
var zNodes = [];

var treeReport = {
    init: function () {
        var self = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            url: "/api/v1/tree-reports",
            dataType: "json",
            type: "GET",
            success: function (data) {
                $.fn.zTree.init($("#treeReports"), self.setting, data);
                $("#selectAll").bind("click", treeReport.selectAll);
            }
        });
    },
    setting: {
        view: {
            addHoverDom: function (treeId, treeNode) {
                var object = $("#" + treeNode.tId + "_span");
                if (treeNode.editNameFlag || $("#addFolderBtn_" + treeNode.tId).length > 0 || $("#addVariableBtn_" + treeNode.tId).length > 0 || $("#addSharedRptBtn_" + treeNode.tId).length > 0) {
                    return;
                }
                var typeFile = treeNode.id.split('-');
                //principal folder
                if (treeNode.isParent == true && (typeFile[0] == 'my' || typeFile[0] == 'pub')) {
                    object.after(treeReport.template.addFolder(treeNode));
                }
                //ramificaciones del principal
                if (treeNode.isParent && treeNode.pId != null) {
                    object.after(treeReport.template.sharedReport(treeNode));
                }
                //reportes
                var isNotPub;
                if(typeof treeNode.pId == 'string' && treeNode.pId.indexOf('-') > -1){
                    var typepId = treeNode.pId.split('-');
                    isNotPub = typepId[0] != 'pub' && typepId[0] != 'sha';
                }
                if (!treeNode.isParent && isNotPub) {
                    object.after(treeReport.template.sharedReport(treeNode));
                }
                var btn = $("#addFolderBtn_" + treeNode.tId);
                if (btn) btn.bind("click", function () {
                    var zTree = $.fn.zTree.getZTreeObj("treeReports");
                    $('#folderModal').modal('show');
                    $('#folder_id').val(treeNode.id);
                    $('#saveFolder').click(function () {
                        treeReport.saveFolder(zTree, treeNode, $('#folder_name').val(), $('#folder_id').val());
                    });
                    return false;
                });
                var btnShared = $("#addSharedRptBtn_" + treeNode.tId);
                if (btnShared) btnShared.bind("click", function () {
                    var zTree = $.fn.zTree.getZTreeObj("treeReports");
                    $('#sharedModal').modal('show');
                    $( ".shared-variable-complete" ).autocomplete( "option", "appendTo", ".eventInsForm" );
                    $('#variable_id').val(treeNode.id);
                    $('#saveSharedReport').click(function () {
                        treeReport.saveSharedReport(zTree, treeNode, $('#shared_name').val());
                    });
                    return false;
                });
            },
            removeHoverDom: function (treeId, treeNode) {
                $("#addSharedRptBtn_" + treeNode.tId).unbind().remove();
                $("#addFolderBtn_" + treeNode.tId).unbind().remove();
                //$("#addVariableBtn_" + treeNode.tId).unbind().remove();
            },
            selectedMulti: false
        },
        edit: {
            enable: true,
            editNameSelectAll: true,
            showRemoveBtn: function (treeId, treeNode) {
                var flag = false;
                var typeFile = treeNode.id.split('-');
                if (treeNode.id != 'my-001' && typeFile[0] == 'my') {
                    flag = true;
                } else if (typeFile[0] == 'var') {
                    flag = true;
                }
                var isNotPub;
                if(typeof treeNode.pId == 'string' && treeNode.pId.indexOf('-') > -1){
                    var typepId = treeNode.pId.split('-');
                    isNotPub = typepId[0] != 'pub';
                }
                if (!treeNode.isParent && isNotPub) {
                    flag = true;
                }

                return flag;
            },
            showRenameBtn: function (treeId, treeNode) {
                var flag = false;
                var typeFile = treeNode.id.split('-');
                if (treeNode.id != 'my-001' && typeFile[0] == 'my') {
                    flag = true;
                } else if (typeFile[0] == 'var') {
                    flag = true;
                }
                return flag;
            },
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeDrag: function (treeId, treeNodes) {
                return false;
            },
            beforeEditName: function (treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    showLog("[ " + getTime() + " beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    var zTree = $.fn.zTree.getZTreeObj("treeReports");
    zTree.selectNode(treeNode);
    //confirm
    //return confirm("Start node '" + treeNode.name + "' editorial status?");
},
            beforeRemove: function (treeId, treeNode) {
                className = (className === "dark" ? "" : "dark");
                showLog("[ " + getTime() + " beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
                var zTree = $.fn.zTree.getZTreeObj("treeReports");
                zTree.selectNode(treeNode);
                return confirm("Confirm delete node '" + treeNode.name + "-" + treeNode.id + "' it?");
            },
            beforeRename: function (treeId, treeNode, newName, isCancel) {
                className = (className === "dark" ? "" : "dark");
                showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
                if (newName.length == 0) {
                    alert("Debe introducir el nombre.");
                    var zTree = $.fn.zTree.getZTreeObj("treeReports");
                    setTimeout(function () {
                        zTree.editName(treeNode)
                    }, 10);
                    return false;
                }
                return true;
            },
            onRemove: function (e, treeId, treeNode) {
                if (treeNode.isParent) {
                    removeFolder(treeNode);
                } else {
                    removeVariable(treeNode);
                }
                //showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
            },
            onRename: function (e, treeId, treeNode, isCancel) {
                if (treeNode.isParent) {
                    updateFolder(treeNode, isCancel);
                } else {
                    updateVariable(treeNode, isCancel);
                }
            }
        }
    }
};

treeReport.selectAll = function () {
    var zTree = $.fn.zTree.getZTreeObj("treeReports");
    zTree.setting.edit.editNameSelectAll = $("#selectAll").attr("checked");
};

treeReport.template = {
    addFolder: function (treeNode) {
        return "<span class='button add' id='addFolderBtn_" + treeNode.tId
            + "' title='Añadir Folder' onfocus='this.blur();'></span>";
    },
    sharedReport: function (treeNode) {
        return "<span class='button shared' id='addSharedRptBtn_" + treeNode.tId
            + "' title='Compartir Reporte(s)' onfocus='this.blur();'></span>";
    }
};

treeReport.saveFolder = function (tree, treeNode, folderName, parentId) {
    if (folderUID.indexOf(parentId) != -1) {
        parentId = 0;
    } else {
        parent = parentId.split('-');
        parentId = parent[1];
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: "/api/v1/folders",
        dataType: "json",
        type: "POST",
        data: {
            name: folderName,
            parent_id: parentId
        },
        success: function (data) {
            $('#folder_id').val(0);
            $('#folder_name').val('');
            tree.addNodes(treeNode, {
                id: parent[0] + '-' + data.id,
                pId: parentId,
                isParent: true,
                name: folderName
            });
            $('#folderModal').modal('hide');
        }
    });
};

treeReport.saveSharedReport = function (tree, treeNode, email) {
    if (folderUID.indexOf(treeNode.id) != -1) {
        variableId = 0;
    } else {
        parent = treeNode.id.split('-');
        variableId = parent[1];
    }
    $folderId = treeNode.pId.split('-');
    $folderId = $folderId[1];
    $.ajax({
        url: "/api/v1/sharedVariable",
        dataType: "json",
        type: "POST",
        data: {
            variableId: variableId,
            type: 'SHARED',
            email: email,
            folder_id: $folderId
        },
        success: function (data) {
            $('#variable_id').val(0);
            $('#shared_name').val('');
            $('#sharedModal').modal('hide');
        }
    });
};
//auto complete
// $( "#shared_name" ).autocomplete({
//     source: function( request, response ) {
//         $.ajax( {
//             url: "/api/v1/sharedVariable/email",
//             dataType: "json",
//             data: {
//                 q: request.term
//             },
//             success: function( data ) {
//                 $('.ui-helper-hidden-accessible').attr('style', 'display: none')
//                 // Handle 'no match' indicated by [ "" ] response
//                 response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
//             }
//         } );
//     },
//     minLength: 3,
//     select: function( event, ui ) {
//         //
//     }
// } );
$(document).ready(function () {
    treeReport.init();
});


