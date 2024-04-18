<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}

// Include necessary files
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';

// Check if customer_id is set in the POST data
if (isset($_POST['customer_id'])) {
    // Retrieve customer details from the database based on the customer_id
    $customer_id = $_POST['customer_id'];
    $sql = "SELECT c.*, uc.full_name AS created_by_name, uu.full_name AS updated_by_name 
            FROM customer c 
            LEFT JOIN users uc ON c.created_by = uc.user_id 
            LEFT JOIN users uu ON c.updated_by = uu.user_id 
            WHERE c.cust_id = $customer_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Customer found, fetch details
        $customer = $result->fetch_assoc();
    } else {
        // Customer not found, redirect to an error page or handle accordingly
        echo "Customer not found.";
        exit();
    }
} else {
    // Redirect to an error page or handle accordingly if customer_id is not set
    echo "Customer ID not provided.";
    exit();
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card invoice-info-card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <div class="invoice-terms">
                                    <h6>Faahfaahinta Macmiilka:</h6>
                                    <p class="mb-0">Warbixin kooban oo ku saabsan Macmiilka</p>
                                </div>
                                <div class="invoice-terms">
                                    <h6>Macaga Macmiilka:</h6>
                                    <p><?php echo $customer['cust_full_name']; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="invoice-total-card">
                                    <div class="invoice-total-box">
                                        <div class="invoice-total-inner">
                                            <p>Phone Number: <strong><?php echo $customer['phone_number']; ?> </strong></p>
                                            <p>Ciwaanka: <strong><?php echo $customer['address']; ?> </strong></p>
                                            <p>waxa sameeyay: <strong><?php echo $customer['created_by_name']; ?> </strong></p>
                                            <p>waxka badalay: <strong><?php echo $customer['updated_by_name']; ?> </strong></p>
                                            <p>taariikhda lasameeyay: <strong><?php echo date('j-M-Y', strtotime($customer['created_at'])); ?></strong></p>
                                            <p>taariikhda waxkabadalka: <strong><?php echo date('j-M-Y', strtotime($customer['updated_at'])); ?> </strong> </p>
                                        </div>
                                        <div class="invoice-total-footer">
                                            <h4>Total Amount <span><?php echo $customer['grand_balance']; ?></span></h4>
                                        </div>
                                    </div>
                                    <div class="mt-3"> <!-- Add margin top for space -->
                                        <div class="btn-group me-3" role="group" aria-label="Invoice Buttons">
                                            <form id="deleteForm" action="delete_customer.php" method="post">
                                                <input type="hidden" name="customer_id" value="<?php echo $customer['cust_id']; ?>">
                                                <button type="submit" class="btn btn-warning mx-2">Delete</button>
                                            </form>
                                            <button type="button" class="btn btn-primary mx-2" onclick="window.location.href = 'all_customers.php';">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>