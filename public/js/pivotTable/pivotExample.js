google.load("visualization", "1", {packages:["corechart", "charteditor"]});
$(function(){
    var derivers = $.pivotUtilities.derivers;
    var renderers = $.extend($.pivotUtilities.renderers,
        $.pivotUtilities.gchart_renderers);

    $.getJSON("/data/comercializacion", function(mps) {
        $("#output").pivotUI(
            mps,
            {
                renderers: renderers,
                cols: ["d1_mes"],
                rows: ["variable_estadistica"],
                rendererName: "Area Chart"
            },
            false,
            'es'
        );
    });
});