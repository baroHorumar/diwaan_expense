<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
}
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';
$currency = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['currencies'])) {
    $currency_id = $_POST['currencies'];
    $stmt = $conn->prepare("SELECT * FROM currencies WHERE id = ?");
    $stmt->bind_param("i", $currency_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currency = $result->fetch_assoc();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_currency'])) {
    $currency_id1 = $_POST['currency_id'];
    $currency_name = $_POST['name'];
    $exchange_rate = $_POST['rate'];
    if ($currency_name == 'Somaliland Shilling') {
        $currency_symbol  = 'SL shilling';
    } else {
        $currency_symbol  = '$';
    }
    $is_default = $_POST['is_default'];
    $stmt = $conn->prepare("UPDATE currencies SET name = ?, rate_exchange =?, symbol = ?, is_default = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $currency_name, $exchange_rate, $currency_symbol, $is_default, $currency_id1);
    $stmt->execute();
    echo '<script>window.location.href = "money.php";</script>';
    exit;
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Lacag</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">kudar Lacag</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Kudar Lacag</h4>
                        <form action="edit_money.php" method="POST">
                            <input type="hidden" name="currency_id" value="<?php echo isset($currency['id']) ? $currency['id'] : ''; ?>">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Magaca Lacagta</label>
                                            <select class="form-control" name="name" required>
                                                <option value="<?php echo isset($currency['name']) ? $currency['name'] : ''; ?>"><?php echo isset($currency['name']) ? $currency['name'] : ''; ?></option>
                                                <option value="Somaliland Shilling">Somaliland Shilling</option>
                                                <option value="Dollar">Dollar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Default</label>
                                            <select class="form-control" name="is_default" required>
                                                <option value="<?php echo isset($currency['is_default']) ? $currency['is_default'] : ''; ?>"><?php echo $currency['is_default'];  ?></option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Exchange Rate</label>
                                            <input type="text" class="form-control" name="rate" value="<?php echo $currency['rate_exchange']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="edit_currency" class="btn btn-primary">Submit</button>
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
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>