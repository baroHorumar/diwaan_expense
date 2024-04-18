<?php
include './includes/conn.php';

// Calculate the first and last day of the current month
$first_day_of_month = date('Y-m-01');
$last_day_of_month = date('Y-m-t');

$stmt = $conn->prepare("SELECT amount FROM expenses WHERE company_id = ? AND created_at BETWEEN ? AND ?");
$stmt->bind_param("iss", $_SESSION['company_id'], $first_day_of_month, $last_day_of_month);
$stmt->execute();
$result = $stmt->get_result();

$total_expenses = 0;
while ($row = $result->fetch_assoc()) {
    $total_expenses += $row['amount'];
}

// Fetch income where company_id matches and days are within the current month
$stmt = $conn->prepare("SELECT amount FROM expenses WHERE company_id = ? AND created_at BETWEEN ? AND ? AND status = 'INCOME'");
$stmt->bind_param("iss", $_SESSION['company_id'], $first_day_of_month, $last_day_of_month);
$stmt->execute();
$result = $stmt->get_result();

$total_income = 0;
while ($row = $result->fetch_assoc()) {
    $total_income += $row['amount'];
}

// Data for the pie chart
$pieXArray = ["Expenses", "Income"];
$pieYArray = [$total_expenses, $total_income];

?>

<div class="col-xl-6 d-flex">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Pie Chart</div>
        </div>
        <div class="card-body">
            <div id="myPlot1" style="width:100%;max-width:900px;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
    // Data for the pie chart
    const pieXArray = <?php echo json_encode($pieXArray); ?>;
    const pieYArray = <?php echo json_encode($pieYArray); ?>;
    const pieData = [{
        labels: pieXArray,
        values: pieYArray,
        type: "pie"
    }];

    const pieLayout = {
        title: "",
        font: {
            size: 18 // Adjust the font size as needed
        },
        margin: {
            l: 30, // left margin
            r: 70, // right margin
            // bottom margin
        }
    };

    Plotly.newPlot("myPlot1", pieData, pieLayout);
</script>