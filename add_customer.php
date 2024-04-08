<?php
session_start();
include './includes/header.php';
include './includes/sidebar.php';
include './includes/conn.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-customer'])) {
    // Retrieve form data
    $cust_full_name = $_POST['cust_full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $grand_balance = 0; // Set grand_balance to 0
    $created_by = $_SESSION['user_id'];
    $updated_by = $_SESSION['user_id'];
    $company_id = $_SESSION['company_id'];

    // Validate form data
    if (empty($cust_full_name) || empty($phone_number) || empty($address)) {
        echo "All fields are required.";
    } else {

        $stmt = $conn->prepare("INSERT INTO customer (cust_full_name, phone_number, address, grand_balance, created_by, updated_by, company_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdiii", $cust_full_name, $phone_number, $address, $grand_balance, $created_by, $updated_by, $company_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'all_customers.php';</script>";
            exit;
        } else {
            // Error message
            echo "Error adding customer: " . $stmt->error;
        }
    }
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Macmiil</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">kudar Macmiil</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Kudar Macmiil</h4>
                        <form action="add_customer.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" required class="form-control" name="cust_full_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="save-customer" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require './includes/footer.php';
?>