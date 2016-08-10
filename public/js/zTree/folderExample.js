var log, className = "dark";
var beforeDrag = function (treeId, treeNodes) {
    return false;
};

var beforeEditName = function (treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    showLog("[ " + getTime() + " beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    zTree.selectNode(treeNode);
    //confirm
    //return confirm("Start node '" + treeNode.name + "' editorial status?");
};

var beforeRemove = function (treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    showLog("[ " + getTime() + " beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    zTree.selectNode(treeNode);
    return confirm("Confirm delete node '" + treeNode.name + "' it?");
};

var onRemove = function (e, treeId, treeNode) {
    showLog("[ " + getTime() + " onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
};

var beforeRename = function (treeId, treeNode, newName, isCancel) {
    className = (className === "dark" ? "" : "dark");
    showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
    if (newName.length == 0) {
        alert("Node name can not be empty.");
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        setTimeout(function () {
            zTree.editName(treeNode)
        }, 10);
        return false;
    }
    return true;
};

var onRename = function (e, treeId, treeNode, isCancel) {
    showLog((isCancel ? "<span style='color:red'>" : "") + "[ " + getTime() + " onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>" : ""));
};

var showRemoveBtn = function (treeId, treeNode) {
    return !treeNode.isFirstNode;
};

var showRenameBtn = function (treeId, treeNode) {
    return true;
    return !treeNode.isLastNode;
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

var newCount = 1;
var addHoverDom = function (treeId, treeNode) {
    var sObj = $("#" + treeNode.tId + "_span");
    if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) return;
    var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
        + "' title='add node' onfocus='this.blur();'></span>";
    sObj.after(addStr);
    var btn = $("#addBtn_" + treeNode.tId);
    if (btn) btn.bind("click", function () {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.addNodes(treeNode, {id: (100 + newCount), pId: treeNode.id, name: "new node" + (newCount++)});
        return false;
    });
};

var removeHoverDom = function (treeId, treeNode) {
    $("#addBtn_" + treeNode.tId).unbind().remove();
};

var selectAll = function () {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
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

var zNodes = [
    {id: 1, pId: 0, name: "parent node 1", open: true},
    {id: 2, pId: 0, name: "parent node 2", open: true},
    {id: 3, pId: 0, name: "parent node 3", open: true},
    {id: 4, pId: 0, name: "parent node 4", isParent: true},
    {id: 5, pId: 0, name: "parent node 5", isParent: true},
    {id: 11, pId: 1, name: "leaf node 1-1"},
    {id: 12, pId: 1, name: "leaf node 1-2"},
    {id: 13, pId: 1, name: "leaf node 1-3"},

    {id: 21, pId: 2, name: "leaf node 2-1"},
    {id: 22, pId: 2, name: "leaf node 2-2"},
    {id: 23, pId: 2, name: "leaf node 2-3"},

    {id: 31, pId: 3, name: "leaf node 3-1"},
    {id: 32, pId: 3, name: "leaf node 3-2"},
    {id: 33, pId: 3, name: "leaf node 3-3"}
];


$(document).ready(function () {
    $.ajax({
        url: "/api/v1/folders",
        dataType: "json",
        type: "GET",
        data: {"reports": "marco"},
        success: function (data) {
            zNodes = [];
            $.each(data, function (index, value) {
                var node = {};
                node.id = value.id;
                node.name = value.name;
                node.pId = value.parent_id;
                if (value.parent_id == 0) {
                    node.isParent = true;
                }

                //{id: 5, pId: 0, name: "parent node 5", isParent:true},
                console.log(index);
                console.log(value);
                zNodes.push(node);
            });

            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            $("#selectAll").bind("click", selectAll);
        }
    });

    //$.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //$("#selectAll").bind("click", selectAll);
});