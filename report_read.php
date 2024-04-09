<?php
include('./includes/header.php');
include('./includes/sidebar.php');
include('./includes/conn.php');
if (isset($_POST['report_read'])) {
    $status = $_POST['status'];
    $start_date = $_POST['from_date'];
    $end_date = $_POST['to_date'];
    $query = "SELECT expense_id, amount, description, created_at FROM expenses WHERE created_at BETWEEN '$start_date' AND '$end_date' AND status = '$status'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } else {
?>
        <div class="page-wrapper">
            <div class="content container-fluid">
                <header class="mt-6"> <!-- Added mt-4 class for top margin -->
                    <h1 class="report-title">Eelo University</h1>
                    <h2 class="report-subtitle">Library Management System</h2>
                    <address>
                        <p>saylici Road, Borama, Awdal, Somaliland.</p>
                        <p>(+252) 63 4456789</p>
                    </address>
                </header>
                <div class="page-header">
                    <h3 class="page-title">Expense Details</h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Expense ID</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['expense_id'] . "</td>";
                                        echo "<td>" . $row['amount'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center print-button">
                <button class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>

<?php
    }
}
include('./includes/footer.php');
?>