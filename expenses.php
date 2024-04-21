<?php
session_start();
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}
$expenses = array();
$company_id = $_SESSION['company_id'];
if (!isset($_POST['status']) && !isset($_POST['wakhti'])) {
    $sql = "SELECT * FROM expenses WHERE company_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['created_at'] = date('j-M-Y', strtotime($row['created_at'])); // Format date
        $expenses[] = $row;
    }
} else if (isset($_POST['status']) && isset($_POST['wakhti'])) {

    $status = $_POST['status'] ?? '';
    $wakhti = $_POST['wakhti'] ?? '';
    if ($status == 'All' && $wakhti == 'All') {
        $sql = "SELECT * FROM expenses WHERE company_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['created_at'] = date('j-M-Y', strtotime($row['created_at'])); // Format date
            $expenses[] = $row;
        }
    }
    if ($status == 'Dakhli') {
        $status_condition = "status = 'INCOME'";
    } else {
        $status_condition = "status = 'EXPENSE'";
    }
    if ($wakhti == 'Maanta') {
        $date_condition = "DATE(created_at) = CURDATE()";
    } elseif ($wakhti == 'Todobaadkan') {
        $start_of_week = date('Y-m-d', strtotime('monday this week'));
        $date_condition = "created_at BETWEEN '$start_of_week' AND CURDATE()";
    } elseif ($wakhti == 'Bishan') {
        $start_of_month = date('Y-m-01');
        $date_condition = "created_at BETWEEN '$start_of_month' AND CURDATE()";
    } else {
        $date_condition = '';
    }
    $sql = "SELECT * FROM expenses WHERE company_id = ?";
    if ($status_condition) {
        $sql .= " AND $status_condition";
    }
    if ($date_condition) {
        $sql .= " AND $date_condition";
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['created_at'] = date('j-M-Y', strtotime($row['created_at'])); // Format date
        $expenses[] = $row;
    }
}
?>
<style>
    /* General card styling */
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        background-color: #ffffff;
        cursor: pointer;
        /* Add cursor pointer */
    }

    /* Card body styling */
    .card-body {
        padding: 20px;
    }

    /* Dash widget header styling */
    .dash-widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Dash count styling (parent) */
    .dash-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-right: 20px;
        /* Add some space between the counts and the icon */
    }

    /* Expense/date title styling (children) */
    .expense-title,
    .date-title {
        font-weight: bold;
        margin-bottom: 5px;
        color: #333333;
        /* Darken the title color */
    }

    /* Expense/date values styling (children) */
    .dash-counts p {
        margin-bottom: 5px;
        /* Add space between values */
        color: #666666;
        /* Adjust the value color */
    }

    /* Icon styling */
    .dash-widget-icon {
        color: #ffffff;
        border-radius: 50%;
        padding: 15px;
        font-size: 24px;
    }
</style>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="add_expenses.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div id="filter_inputs" class="card filter-card">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-5 align-items-center">
                                <div class="form-group">
                                    <label>Dooro Nooc</label>
                                    <select name="status" class="form-control">
                                        <option value="">Dooro</option>
                                        <option value="All">Dhamaan</option>
                                        <option value="Dakhli">Dakhli</option>
                                        <option value="Kharash">Kharash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-5 align-items-center">
                                <div class="form-group">
                                    <label>Taariikh</label>
                                    <select name="wakhti" class="form-control">
                                        <option value="">Dooro Wakhtiga</option>
                                        <option value="All">Dhamaan</option>
                                        <option value="Maanta">Maanta</option>
                                        <option value="Todobaadkan">Todobaadkan</option>
                                        <option value="Bishan">Bishan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 align-items-center"> <!-- Adjust column width and offset -->
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <?php foreach ($expenses as $expense) : ?>
            <div class="col-xl-4 col-sm-6 col-12">
                <a href="expense_details.php?expense_id=<?php echo $expense['expense_id']; ?>" class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon <?php echo ($expense['status'] == 'INCOME') ? 'bg-3' : 'bg-1'; ?>">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <div class="dash-count">
                                <div class="expense-title"><?php echo $expense['status']; ?></div>
                                <div class="dash-counts">
                                    <p><?php echo $expense['amount']; ?></p>
                                </div>
                            </div>
                            <div class="dash-count">
                                <div class="date-title">Date</div>
                                <div class="dash-counts">
                                    <p><?php echo $expense['created_at']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

        <?php if (empty($expenses)) : ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Wax Dakhli/Kharash ah lama helin oo ku habboon shuruudahaaga </div>
            </div>
        <?php endif; ?>

    </div>
</div>
<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/js/script.js"></script>
</body>

</html>
