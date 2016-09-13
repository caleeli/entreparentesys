var mpsVar, renderersVar;
var beforeDrag = function (treeId, treeNodes) {
    for (var i = 0, l = treeNodes.length; i < l; i++) {
        if (treeNodes[i].drag === false) {
            return false;
        }
    }
    return true;
}
var beforeDrop = function (treeId, treeNodes, targetNode, moveType) {
    if (targetNode.id == 'var-001' || targetNode.id == 'rows-001' || targetNode.id == 'cols-001' ) {
        return targetNode ? targetNode.drop !== false : true;
    } else {
        return false;
    }
}

var setCheck = function () {
    var zTree = $.fn.zTree.getZTreeObj("variablesTree");
    zTree.setting.edit.drag.isCopy = false;
    zTree.setting.edit.drag.isMove = true;
    zTree.setting.edit.drag.prev = true;
    zTree.setting.edit.drag.inner = true;
    zTree.setting.edit.drag.next = true;
};


var generateVariables = function () {
    var zTree = $.fn.zTree.getZTreeObj("variablesTree");
    var nodes = zTree.getNodes();
    var rows = [];
    var cols = [];
    $.each(nodes, function (index, value) {
        switch (index) {
            case 1:
                $.each(value.children, function (indexVar, variables) {
                    rows.push(variables.name);
                });
                break;
            case 2:
                $.each(value.children, function (indexVar, variables) {
                    cols.push(variables.name);
                });
                break;
        }
    });
    if (cols.length == 0 && rows.length == 0) {
        return true;
    }
    console.log(cols);
    console.log(rows);

    $("#outputGrafico").pivotUI(
        mpsVar,
        {
            renderers: renderersVar,
            cols: cols,
            rows: rows,
            vals: [cols[1]],
            rendererName: $('.pvtRenderer').val()
        },
        true,
        'es'
    );
    var tr = $('tr', '#outputGrafico');
    tr[0].style = 'display:none';

    $('.pvtAxisContainer').attr('style', 'display:none');
    $('.pvtRenderer').attr('style', 'width:130px');
    $('.pvtAggregator').attr('style', 'width:130px');

    $('#selectRender').html('');
    $('#selectAgregator').html('');
    $('#selectRender').html($('.pvtRenderer'));
    $('#selectAgregator').html($('.pvtAggregator'));
};


var settingVar = {
    edit: {
        enable: true,
        showRemoveBtn: false,
        showRenameBtn: false
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        beforeDrag: beforeDrag,
        beforeDrop: beforeDrop
    }
};

var generateVariablesTree = function (data, mps, renderers) {
    mpsVar = mps;
    renderersVar = renderers;
    var zNodesVar = [];
    var node = {};
    node.id = 'var-001';
    node.name = 'Variables';
    node.pId = 0;
    node.isParent = true;
    node.drag = false;
    node.open =  true;
    zNodesVar.push(node);
    $.each(data, function (index, value) {
        var node = {};
        node.id = 'var-' + index;
        node.name = value;
        node.pId = 'var-001';
        node.isParent = false;
        node.drag = true;
        zNodesVar.push(node);
    });
    var node = {};
    node.id = 'rows-001'
    node.name = 'Rows';
    node.pId = 0;
    node.isParent = true;
    node.drag = false;
    zNodesVar.push(node);

    var node = {};
    node.id = 'cols-001';
    node.name = 'Columns';
    node.pId = 0;
    node.isParent = true;
    node.drag = false;
    zNodesVar.push(node);


    $.fn.zTree.init($("#variablesTree"), settingVar, zNodesVar);
    setCheck();
    $("#copy").bind("change", setCheck);
    $("#move").bind("change", setCheck);
    $("#prev").bind("change", setCheck);
    $("#inner").bind("change", setCheck);
    $("#next").bind("change", setCheck);
};

