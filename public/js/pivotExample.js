$(function () {


    $("#output").pivotUI(
        [
            {color: "blue", shape: "circle"},
            {color: "red", shape: "triangle"},
            {color: "blue", shape: "square"},
            {color: "red", shape: "circle"},
            {color: "blue", shape: "sphere"}
        ],
        {
            rows: ["color"],
            cols: ["shape"]
        }
    );
});