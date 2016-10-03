//Elimnar este archivo

//google.load("visualization", "1", {packages: ["corechart", "charteditor"]});
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
        console.log(mps);
        console.log(attributes);
        $("#outputGrafico").pivotUI(
            mps,
            {
                renderers: renderers,
                //cols: attributes.splice(2),
                //rows: ["variable_estadistica"],
                rendererName: "Area Chart"
            },
            false,
            'es'
        );

        //hidden fields
        var tr = $('tr', '#outputGrafico');
        tr[0].style = 'display:none';
        //tr[1].style = 'display:none';

        $('.pvtAxisContainer').attr('style', 'display:none');
        $('.pvtRenderer').attr('style', 'width:130px');
        $('.pvtAggregator').attr('style', 'width:130px');

        generateVariablesTree(attributes, mps, renderers);

        $('#selectRender').html($('.pvtRenderer'));
        $('#selectAgregator').html($('.pvtAggregator'));

    });
});