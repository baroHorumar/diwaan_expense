<?php
include './includes/conn.php';

// Fetch the last 10 days' income from expenses table
$query = "SELECT DATE(date) AS day, SUM(amount) AS total_income 
          FROM expenses 
          WHERE status = 'income' AND company_id = '" . $_SESSION['company_id'] . "' 
          GROUP BY DATE(date) 
          ORDER BY DATE(date) DESC 
          LIMIT 10";
$result = mysqli_query($conn, $query);

// Initialize arrays to store data for the chart
$dates = [];
$incomes = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Format the date as month and day
    $date = date('M d', strtotime($row['day']));
    $dates[] = $date;
    $incomes[] = $row['total_income'];
}
?>

<div class="col-xl-6 d-flex">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Last 10 Days' Income</div>
        </div>
        <div class="card-body">
            <div id="myPlot" style="width:100%;max-width:900px;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<script>
    const xArray = <?php echo json_encode($incomes); ?>;
    const yArray = <?php echo json_encode($dates); ?>;

    const data = [{
        x: xArray,
        y: yArray,
        type: "bar",
        orientation: "h",
        marker: {
            color: "rgba(255,0,0,0.6)"
        }
    }];

    const layout = {
        title: "",
        font: {
            size: 18 // Adjust the font size as needed
        },
        margin: {
            l: 100, // left margin
            r: 0.6 // bottom margin
        }
    };

    Plotly.newPlot("myPlot", data, layout);
</script>