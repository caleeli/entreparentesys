var log, className = "dark";
var beforeDrag = function (treeId, treeNodes) {
    return false;
};

var beforeEditName = function (treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    showLog("[ " + getTime() + " beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    var zTree = $.fn.zTree.getZTreeObj("foldersTree");
    zTree.selectNode(treeNode);
    //confirm
    //return confirm("Start node '" + treeNode.name + "' editorial status?");
};

var beforeRemove = function (treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    showLog("[ " + getTime() + " beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    var zTree = $.fn.zTree.getZTreeObj("foldersTree");
    zTree.selectNode(treeNode);
    return confirm("Confirm delete node '" + treeNode.name + "-" + treeNode.id + "' it?");
};

var removeFolder = function (treeNode) {
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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/api/v1/variable/" + folderId,
        //dataType: "json",
        type: "DELETE",
        success: function (data) {
            //change
            showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
            loadFolderTree();
        }
    });
};

var onRemove = function (e, treeId, treeNode) {
    if (treeNode.isParent) {
        removeFolder(treeNode);
    } else {
        removeVariable(treeNode);
    }
    //showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
};

var beforeRename = function (treeId, treeNode, newName, isCancel) {
    className = (className === "dark" ? "" : "dark");
    showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
    if (newName.length == 0) {
        alert("Debe introducir el nombre.");
        var zTree = $.fn.zTree.getZTreeObj("foldersTree");
        setTimeout(function () {
            zTree.editName(treeNode)
        }, 10);
        return false;
    }
    return true;
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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

var onRename = function (e, treeId, treeNode, isCancel) {
    if (treeNode.isParent) {
        updateFolder(treeNode, isCancel);
    } else {
        updateVariable(treeNode, isCancel);
    }
};

var showRemoveBtn = function (treeId, treeNode) {
    var flag = false;
    var typeFile = treeNode.id.split('-');
    if (treeNode.id != 'my-001' && typeFile[0] == 'my') {
        flag = true;
    } else if (typeFile[0] == 'var') {
        flag = true;
    }

    return flag;
};

var showRenameBtn = function (treeId, treeNode) {
    var flag = false;
    var typeFile = treeNode.id.split('-');
    if (treeNode.id != 'my-001' && typeFile[0] == 'my') {
        flag = true;
    } else if (typeFile[0] == 'var') {
        flag = true;
    }
    return flag;
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
var saveFolder = function (tree, treeNode, folderName, parentId) {
    if (folderUID.indexOf(parentId) != -1) {
        parentId = 0;
    } else {
        parent = parentId.split('-');
        parentId = parent[1];
    }
    $.ajax({
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

var saveSharedVariable = function (tree, treeNode, email) {
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
var addHoverDom = function (treeId, treeNode) {

    var object = $("#" + treeNode.tId + "_span");
    if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0 || $("#addVariableBtn_" + treeNode.tId).length > 0 || $("#addSharedBtn_" + treeNode.tId).length > 0) return;

    var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
        + "' title='Añadir Folder' onfocus='this.blur();'></span>";
    var addVariable = "<span class='button variable' id='addVariableBtn_" + treeNode.tId
        + "' title='add Variable Shared' onfocus='this.blur();'></span>";
    var addShared = "<span class='button shared' id='addSharedBtn_" + treeNode.tId
        + "' title='Compartir Variable' onfocus='this.blur();'></span>";

    var typeFile = treeNode.id.split('-');

    if (treeNode.isParent == true && typeFile[0] == 'my') {
        object.after(addStr);
        object.after(addVariable);
    }

    if (treeNode.id != 'my-001' && treeNode.id == 'sha-001' && treeNode.id != 'pub-001' && treeNode.isParent == false) {
        object.after(addShared);
    } else if (typeFile[0] == 'var' && treeNode.isParent == false) {
        object.after(addShared);
    }


    var btn = $("#addBtn_" + treeNode.tId);
    if (btn) btn.bind("click", function () {
        var zTree = $.fn.zTree.getZTreeObj("foldersTree");
        $('#folderModal').modal('show');
        $('#folder_id').val(treeNode.id);
        $('#saveFolder').click(function () {
            saveFolder(zTree, treeNode, $('#folder_name').val(), $('#folder_id').val());
        })
        return false;
    });

    var btnVariable = $("#addVariableBtn_" + treeNode.tId);
    if (btnVariable) btnVariable.bind("click", function () {
        var zTree = $.fn.zTree.getZTreeObj("foldersTree");
        $('#variableModal').modal('show');
        $('#folder_id').val(treeNode.id);
        $('#saveVariable').click(function () {
            saveVariable(zTree, treeNode, $('#variable_name').val(), $('#variable_description').val(), $('#folder_id').val());
        })
        return false;
    });


    var btnShared = $("#addSharedBtn_" + treeNode.tId);
    if (btnShared) btnShared.bind("click", function () {
        var zTree = $.fn.zTree.getZTreeObj("foldersTree");
        $('#sharedModal').modal('show');
        $( ".shared-variable-complete" ).autocomplete( "option", "appendTo", ".eventInsForm" );
        $('#variable_id').val(treeNode.id);
        $('#saveSharedVariable').click(function () {
            saveSharedVariable(zTree, treeNode, $('#shared_name').val());
        });
        return false;
    });
};

var removeHoverDom = function (treeId, treeNode) {
    $("#addSharedBtn_" + treeNode.tId).unbind().remove();
    $("#addBtn_" + treeNode.tId).unbind().remove();
    $("#addVariableBtn_" + treeNode.tId).unbind().remove();
};

var selectAll = function () {
    var zTree = $.fn.zTree.getZTreeObj("foldersTree");
    zTree.setting.edit.editNameSelectAll = $("#selectAll").attr("checked");
};

var setting = {
    view: {
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
        selectedMulti: false
    },
    edit: {
        enable: true,
        editNameSelectAll: true,
        showRemoveBtn: showRemoveBtn,
        showRenameBtn: showRenameBtn
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        beforeDrag: beforeDrag,
        beforeEditName: beforeEditName,
        beforeRemove: beforeRemove,
        beforeRename: beforeRename,
        onRemove: onRemove,
        onRename: onRename
    }
};

var zNodes = [];

var loadFolderTree = function () {
    $.ajax({
        url: "/api/v1/folders",
        dataType: "json",
        type: "GET",
        success: function (data) {
            zNodes = [];
            $.each(data, function (index, value) {
                var node = {};
                node.id = value.id;
                node.name = value.name;
                node.pId = value.parent_id;
                if (value.folder) {
                    node.isParent = true;
                }

                zNodes.push(node);
            });

            $.fn.zTree.init($("#foldersTree"), setting, zNodes);
            $('#variablesPivotRender').attr('style', 'margin-left: 10px;')
            $("#selectAll").bind("click", selectAll);
        }
    });
};

//auto complete
$( "#shared_name" ).autocomplete({
    source: function( request, response ) {
        $.ajax( {
            url: "/api/v1/sharedVariable/email",
            dataType: "json",
            data: {
                q: request.term
            },
            success: function( data ) {
                $('.ui-helper-hidden-accessible').attr('style', 'display: none')
                // Handle 'no match' indicated by [ "" ] response
                response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
            }
        } );
    },
    minLength: 3,
    select: function( event, ui ) {
        //
    }
} );
$(document).ready(function () {
    loadFolderTree();
});


