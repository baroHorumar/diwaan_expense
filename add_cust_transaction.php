<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require './includes/header.php';
require './includes/sidebar.php';
include './includes/conn.php';
// if (isset($_POST['save-customer1'])) {
//     if (isset($_GET['customer_id'])) {
//         $customer_id = $_GET['customer_id'];
//         $amount = $_POST['amount'];
//         $date = $_POST['date']; // Assuming this is the date field
//         $status = $_POST['status'];
//         $trans_payment = $_POST['trans_payment'];
//         $description = $_POST['description'];

//         if (empty($amount) || empty($status) || empty($trans_payment)) {
//             echo "All fields are required.";
//         } else {
//             $user_id = $_SESSION['user_id'];
//             $company_id = $_SESSION['company_id'];
//             $default_user_id = 1; // Default user ID
//             $grand_balance_column = '';
//             if ($trans_payment == 'Dollar') {
//                 $grand_balance_column = 'grand_balance';
//             } elseif ($trans_payment == 'Sl sh') {
//                 $grand_balance_column = 'grand_balance_sl';
//             } else {
//                 $grand_balance_column = 'grand_balance_bir';
//             }

//             $query = "SELECT $grand_balance_column FROM customer WHERE cust_id = ?";
//             $stmt = $conn->prepare($query);
//             $stmt->bind_param("i", $customer_id);
//             $stmt->execute();
//             $result = $stmt->get_result();
//             $row = $result->fetch_assoc();
//             $grand_balance = $row[$grand_balance_column];

//             if ($status == 'Dhigasho') {
//                 $current_balance = $grand_balance + $amount;
//             } else {
//                 $current_balance = $grand_balance - $amount;
//             }

//             $update_query = "UPDATE customer SET $grand_balance_column = ? WHERE cust_id = ?";
//             $update_stmt = $conn->prepare($update_query);
//             $update_stmt->bind_param("di", $current_balance, $customer_id);
//             $update_stmt->execute();

//             if (!empty($date)) {
//                 $sql = "INSERT INTO cust_transaction (amount, status, money_type, customer_id, created_by, updated_by, company_id, description, total, return_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//                 $stmt = $conn->prepare($sql);
//                 $stmt->bind_param("dsssiisssd", $amount, $status, $trans_payment, $customer_id, $user_id, $default_user_id, $company_id, $description, $current_balance, $date);
//             } else {
//                 $sql = "INSERT INTO cust_transaction (amount, status, money_type, customer_id, created_by, updated_by, company_id, description, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
//                 $stmt = $conn->prepare($sql);
//                 $stmt->bind_param("dsssiiisd", $amount, $status, $trans_payment, $customer_id, $user_id, $default_user_id, $company_id, $description, $current_balance);
//             }

//             if ($stmt->execute()) {
//                 header("Location: customer_details.php?customer_id=$customer_id");
//                 exit;
//             } else {
//                 echo "Error adding customer transaction: " . $stmt->error;
//             }
//         }
//     } else {
//         echo 'Customer ID not provided';
//     }
// }

if (empty($amount) || empty($status) || empty($trans_payment)) {
    echo "All fields are required.";
} else {
    $user_id = $_SESSION['user_id'];
    $company_id = $_SESSION['company_id'];
    $default_user_id = 1; // Default user ID
    $grand_balance_column = '';

    // Determine the grand balance column based on the transaction payment type
    if ($trans_payment == 'Dollar') {
        $grand_balance_column = 'grand_balance';
    } elseif ($trans_payment == 'Sl sh') {
        $grand_balance_column = 'grand_balance_sl';
    } else {
        $grand_balance_column = 'grand_balance_bir';
    }

    // Set a default grand balance if it's null
    if (is_null($grand_balance)) {
        $default_balance_query = "SELECT $grand_balance_column FROM customer WHERE cust_id = ?";
        $default_stmt = $conn->prepare($default_balance_query);
        $default_stmt->bind_param("i", $customer_id);
        $default_stmt->execute();
        $default_result = $default_stmt->get_result();
        $default_row = $default_result->fetch_assoc();
        $grand_balance = $default_row[$grand_balance_column];
    }

    // Determine the return status based on the status and grand balance
    if ($status == 'Dhigasho') {
        $current_balance = $grand_balance + $amount;
        $return_status = ''; // No return status for Dhigasho
    } elseif ($status == 'Amaah' && $grand_balance <= 0) {
        $current_balance = $grand_balance - $amount;
        $return_status = 'Mabixin'; // Set return status to Mabixin if balance is 0 or less for Amaah
    } else {
        $current_balance = $grand_balance - $amount;
        $return_status = ''; // No return status for other statuses
    }

    // Update the customer's grand balance
    $update_query = "UPDATE customer SET $grand_balance_column = ? WHERE cust_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("di", $current_balance, $customer_id);
    $update_stmt->execute();

    // Prepare and execute the transaction insertion query
    if (!empty($date)) {
        $sql = "INSERT INTO cust_transaction (amount, status, money_type, customer_id, created_by, updated_by, company_id, description, total, return_date, return_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsssiisssds", $amount, $status, $trans_payment, $customer_id, $user_id, $default_user_id, $company_id, $description, $current_balance, $date, $return_status);
    } else {
        $sql = "INSERT INTO cust_transaction (amount, status, money_type, customer_id, created_by, updated_by, company_id, description, total, return_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsssiisssds", $amount, $status, $trans_payment, $customer_id, $user_id, $default_user_id, $company_id, $description, $current_balance, $return_status);
    }

    // Execute the transaction insertion query
    if ($stmt->execute()) {
        header("Location: customer_details.php?customer_id=$customer_id");
        exit;
    } else {
        echo "Error adding customer transaction: " . $stmt->error;
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
                        <form method="POST">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control" name="amount">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="date">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="dhigasho">Lacag Dhigasho</option>
                                    <option value="qaadasho">Lacag Qaadasho</option>
                                    <option value="Dayn">Amaah</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nooca Lacagta</label>
                                <select class="form-control" name="trans_payment">
                                    <option value="Dollar">Dollar</option>
                                    <option value="Sl sh">Kaash</option>
                                    <option value="Bir">Bir</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="save-customer1" class="btn btn-primary">Submit</button>
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
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>