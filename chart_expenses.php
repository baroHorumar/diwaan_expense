<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        /* Adjustments for small screens */
        @media screen and (max-width: 600px) {
            #myPlot {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>

<body>
    <div id="myPlot" style="width:100%;max-width:900px"></div>

    <script>
        const xArray = ["Italy", "France", "Spain", "USA", "Argentina"];
        const yArray = [55, 49, 44, 24, 15];

        const data = [{
            x: xArray,
            y: yArray,
            type: "bar"
        }];

        const layout = {
            title: "World Wide Wine Production",
            font: {
                size: 39 // Adjust the font size as needed
            }
        };

        Plotly.newPlot("myPlot", data, layout);
    </script>

</body>

</html>