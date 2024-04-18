<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit; // Ensure script stops executing after redirection
}

require './includes/header.php';
require './includes/sidebar.php';
include './includes/conn.php';

$customer_id = $_SESSION['cust_id'];
$loged_user = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (isset($_POST['save-customer_transaction'])) {
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $trans_payment = $_POST['trans_payment'];
    if ($trans_payment == 'Dollar') {
        $money_type = '$';
    } elseif ($trans_payment == 'Sl sh') {
        $money_type = 'SL Shillin';
    }

    $query = "SELECT cust_id, grand_balance FROM customer WHERE cust_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_balance = $row['grand_balance'];
        if ($money_type == '$') {
            if ($status == 'dhigasho') {
                $total_grand_balance = $current_balance + $amount;
            } else {
                $total_grand_balance = $current_balance - $amount;
            }
            $insert_query = "INSERT INTO cust_transaction (amount, total, created_by, created_at, status, company_id, customer_id, description, money_type) 
                            VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "ssssssss", $amount, $total_grand_balance, $loged_user, $status, $company_id, $customer_id, $description, $money_type);
            mysqli_stmt_execute($stmt);
        } else {
            $exchange_query = "SELECT rate_exchange FROM currencies WHERE symbol = ? AND company_id = ?";
            $stmt = mysqli_prepare($conn, $exchange_query);
            mysqli_stmt_bind_param($stmt, "ss", $money_type, $company_id);
            mysqli_stmt_execute($stmt);
            $exchange_result = mysqli_stmt_get_result($stmt);

            if (!$exchange_result) {
                echo "Error: " . mysqli_error($conn);
                exit;
            }

            if (mysqli_num_rows($exchange_result) > 0) {
                $exchange_row = mysqli_fetch_assoc($exchange_result);
                $exchange_rate = $exchange_row['rate_exchange'];
                $new_amount = $amount / $exchange_rate;

                if ($status == 'dhigasho') {
                    $total_grand_balance = $current_balance + $new_amount;
                } else {
                    $total_grand_balance = $current_balance - $new_amount;
                }
                $insert_query = "INSERT INTO cust_transaction (amount, total, created_by, created_at, status, company_id, customer_id, description, money_type, exchange_rates) 
                                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_query);
                mysqli_stmt_bind_param($stmt, "sssssssss", $new_amount, $total_grand_balance, $loged_user, $status, $company_id, $customer_id, $description, $money_type, $exchange_rate);
                mysqli_stmt_execute($stmt);
            } else {
                echo "No exchange rate found for currency: $money_type";
                exit;
            }
        }

        // Update customer's grand balance
        $update_query = "UPDATE customer SET grand_balance = ? WHERE cust_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $total_grand_balance, $customer_id);
        mysqli_stmt_execute($stmt);
        $_SESSION['customer_id_go'] = $customer_id;
        echo "<script>window.location.href = 'customer_details.php'</script>";
    } else {
        echo "No rows found for customer ID: $customer_id";
    }
}
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Lacag bixin/qabasho</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Lacag bixin/qabasho</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lacag bixin/qabasho</h4>
                        <form action="add_cust_transaction.php" method="POST">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control" name="amount">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea rows="4" cols="50" class="form-control" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="date">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="dhigasho">Lacag Dhigasho</option>
                                    <option value="qaabasho">Lacag Qabasho</option>
                                    <option value="qaadasho">Lacag Qaadasho</option>
                                    <option value="Dayn">Amaah</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nooca Lacagta</label>
                                <select class="form-control" name="trans_payment">
                                    <option value="Dollar">Dollar</option>
                                    <option value="Sl sh">Kaash</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="save-customer_transaction" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
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

<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/js/script.js"></script>


<!-- <script>
    function redirectToCustomerDetails(customer_id) {
        <?php
        //$_SESSION['customer_id_go'] = $customer_id; // Set the session variable
        ?>
        window.location.href = 'customer_details.php'; // Redirect to customer_details.php
    }
</script> -->

</body>

</html>