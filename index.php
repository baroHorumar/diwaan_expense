<?php
session_start();
if (!$_SESSION['login']) {
    header('location: login.php');
}
require './includes/header.php';
require './includes/conn.php';
require './includes/sidebar.php';
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <?php
                            $stmt = $conn->prepare("SELECT SUM(amount) AS total_income FROM expenses WHERE status = 'INCOME'");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $total_income = $row['total_income'];
                            ?>
                            <div class="dash-count">
                                <div class="dash-title">Wadarta Dakhliga</div>
                                <div class="dash-counts">
                                    <p><?php echo number_format($total_income); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-5" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-danger me-1"><i class="fas fa-arrow-down me-1"></i>1.15%</span> since last week</p>
                     -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-1">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <?php
                            $stmt = $conn->prepare("SELECT SUM(amount) AS total_expenses FROM expenses WHERE status = 'EXPENSE'");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $total_expense = $row['total_expenses'];
                            ?>
                            <div class="dash-count">
                                <div class="dash-title">Wadarta Kharashaadka</div>
                                <div class="dash-counts">
                                    <p><?php echo $total_expense; ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-6" role="progressbar" style="width: 65%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i class="fas fa-arrow-up me-1"></i>2.37%</span> since last week</p>
                     -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-2">
                                <i class="fas fa-users"></i>
                            </span>
                            <?php
                            $company_id = $_SESSION['company_id'];
                            $stmt = $conn->prepare("SELECT COUNT(*) AS customer_count FROM customer WHERE company_id = ?");
                            $stmt->bind_param("i", $company_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $customer_count = $row['customer_count'];
                            ?>
                            <div class="dash-count">
                                <div class="dash-title">Wadarta Macaamiisha</div>
                                <div class="dash-counts">
                                    <p><?php echo number_format($customer_count); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-7" role="progressbar" style="width: 85%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i class="fas fa-arrow-up me-1"></i>3.77%</span> since last week</p>
                     -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-4">
                                <i class="far fa-user"></i>
                            </span>
                            <?php
                            $company_id = $_SESSION['company_id'];
                            $stmt = $conn->prepare("SELECT COUNT(*) AS employee_count FROM employee WHERE company_id = ?");
                            $stmt->bind_param("i", $company_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $employee_count = $row['employee_count'];
                            ?>
                            <div class="dash-count">
                                <div class="dash-title">Shaqaale</div>
                                <div class="dash-counts">
                                    <p><?php echo number_format($employee_count); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-8" role="progressbar" style="width: 45%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-danger me-1"><i class="fas fa-arrow-down me-1"></i>8.68%</span> since last week</p> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <?php require './pie_chart.php'; ?>
            <?php require './income_charts.php'; ?>

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title">Top Ten Most Valuable Clients</h5>
                            </div>
                            <div class="col-auto">
                                <a href="invoices.html" class="btn-right btn btn-sm btn-outline-primary">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Macaga</th>
                                        <th>Numberka</th>
                                        <th>Totalka</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT cust_full_name, phone_number, grand_balance 
                                FROM customer 
                                WHERE grand_balance > 0 AND company_id = '" . $_SESSION['company_id'] . "' 
                                ORDER BY grand_balance DESC 
                                LIMIT 10";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['cust_full_name'] . "</td>";
                                        echo "<td>" . $row['phone_number'] . "</td>";
                                        echo "<td>$" . $row['grand_balance'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title">Recent Estimates</h5>
                            </div>
                            <div class="col-auto">
                                <a href="estimates.html" class="btn-right btn btn-sm btn-outline-primary">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Macaga</th>
                                        <th>Numberka</th>
                                        <th>Totalka</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT cust_full_name, phone_number, grand_balance 
                                  FROM customer 
                                  WHERE grand_balance < 0 AND company_id = '" . $_SESSION['company_id'] . "' 
                                  ORDER BY created_at DESC 
                                  LIMIT 10";
                                    $result = mysqli_query($conn, $query);

                                    // Loop through the results and display them in the table
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['cust_full_name'] . "</td>";
                                        echo "<td>" . $row['phone_number'] . "</td>";
                                        echo "<td>$" . $row['grand_balance'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>