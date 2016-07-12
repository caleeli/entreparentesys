$(function () {
    $("#csv").bind("change", function (event) {
        var reader = new FileReader();
        reader.onload = function (theFile) {
            try {
                var input = $.csv.toArrays(theFile.target.result);

            }
            catch (e) {
                alert("CSV Parse error.");
                return;
            }

            var renderers = $.extend(
                $.pivotUtilities.renderers,
                $.pivotUtilities.c3_renderers,
                $.pivotUtilities.d3_renderers,
                $.pivotUtilities.export_renderers
            );

            $("#output").pivotUI(input, {renderers: renderers}, true, 'es');
        };
        reader.readAsText(event.target.files[0]);
    });
});
