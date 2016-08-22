google.load("visualization", "1", {packages: ["corechart", "charteditor"]});
$(function () {
    var derivers = $.pivotUtilities.derivers;
    var renderers = $.extend($.pivotUtilities.renderers,
        $.pivotUtilities.gchart_renderers);

    $.getJSON("/data/comercializacion", function (mps) {
        var attributes = [];
        if (typeof mps[0] == 'object') {
            for (var a in mps[0]) {
                attributes.push(a);
            }
        }
        $("#output").pivotUI(
            mps,
            {
                renderers: renderers,
                cols: attributes.splice(2),
                rows: ["variable_estadistica"],
                rendererName: "Area Chart"
            },
            false,
            'es'
            );
    });
});