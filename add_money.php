<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
}
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    if ($name == 'Somaliland Shilling') {
        $symbol = 'SL shilling';
    } else {
        $symbol = '$';
    }
    $is_default = $_POST['is_default'];
    $exchange_rate = $_POST['rate'];
    $company_id = $_SESSION['company_id'];
    $stmt = $conn->prepare("INSERT INTO currencies (name, symbol, is_default, rate_exchange , company_id) VALUES (?, ?,  ?, ?,?)");
    $stmt->bind_param("sssii", $name, $symbol, $is_default, $exchange_rate,  $company_id);
    if ($stmt->execute()) {
        echo '<script>
        // Redirect to currencies.php
        window.location.href = \'./money.php\';
    </script>';
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Currency</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="currencies.php">Currencies</a></li>
                        <li class="breadcrumb-item active">Add Currency</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 align-items-center">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Magaca Lacagta</label>
                                        <select class="form-control" name="name" required>
                                            <option value="">Dooro Magaca Lacagta:</option>
                                            <option value="Somaliland Shilling">Somaliland Shilling</option>
                                            <option value="Dollar">Dollar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Default</label>
                                        <select class="form-control" name="is_default" required>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rate Exchange</label>
                                        <input type="text" class="form-control" name="rate" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Add Currency</button>
                            </div>
                        </form>
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
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>