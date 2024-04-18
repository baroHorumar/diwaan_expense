<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';
$customers = array(); // Initialize customers array
$company_id = $_SESSION['company_id']; // Example company ID
$sql = "SELECT c.*, uc.full_name AS created_by, uu.full_name AS updated_by FROM customer c LEFT JOIN users uc ON c.created_by = uc.user_id LEFT JOIN users uu ON c.updated_by = uu.user_id WHERE c.company_id = 12 ORDER BY c.created_at DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title text-center">Dhamaan Macaamiisha</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Invoice Grid</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="add_customer.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                    <!-- <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a> -->
                </div>
            </div>
        </div>
        <div id="filter_inputs" class="card filter-card">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status:</label>
                            <select class="select">
                                <option>Select Status</option>
                                <option>Draft</option>
                                <option>Sent</option>
                                <option>Viewed</option>
                                <option>Expired</option>
                                <option>Accepted</option>
                                <option>Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From</label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To</label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($customers as $customer) : ?>
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card <?php echo $customer['grand_balance'] < 0 ? 'bg-warning-light' : 'bg-success-light'; ?>">
                        <div class="card-body">
                            <div class="inv-header mb-3">
                                <script>
                                    function redirectToCustomerDetails(customer_id) {
                                        $.ajax({
                                            url: 'set_session.php', // PHP script to set the session variable
                                            method: 'POST',
                                            data: {
                                                customer_id: customer_id
                                            },
                                            success: function(response) {
                                                // Redirect to customer_details.php after setting the session variable
                                                window.location.href = 'customer_details.php';
                                            },
                                            error: function(xhr, status, error) {
                                                console.error(xhr.responseText); // Log any errors
                                            }
                                        });
                                    }
                                </script>
                                <a class="text-dark" href="#" onclick="redirectToCustomerDetails(<?php echo $customer['cust_id']; ?>);">
                                    <h3 class="text-center"><?php echo $customer['cust_full_name']; ?></h3>
                                </a>

                            </div>
                            <div class="invoice-id mb-3">
                                <p class="text-yellow text-center ">Phone Number:<?php echo ' ' . $customer['phone_number']; ?></p>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <span class="text-sm text-muted"><i class="far fa-money-bill-alt"></i>Hadhaa</span>
                                    <h6 class="mb-0">$<?php echo $customer['grand_balance']; ?></h6>
                                </div>
                                <div class="col-auto text-end">
                                    <span class="text-sm text-muted"><i class="far fa-calendar-alt"></i>La Abuuray</span>
                                    <h6 class="mb-0"><?php echo date('j-M-Y', strtotime($customer['created_at'])); ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-success-light"><?php echo $customer['address']; ?></span>
                                </div>
                                <div class="col d-flex justify-content-end">
                                    <form action="edit_customer.php" method="POST" class="me-2">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer['cust_id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill circle-btn c" name="update_customer">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="single_customer_details.php" method="POST">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer['cust_id']; ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm rounded-pill circle-btn c" name="single-customer">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    function deleteCustomer() {
        var customerId = document.getElementById('customerIdInput').value;
        console.log('Deleting customer with ID:', customerId);

        // AJAX request to delete customer
        $.ajax({
            url: 'delete_customer.php',
            type: 'POST',
            data: {
                customer_id: customerId
            },
            success: function(response) {
                console.log('Customer deleted successfully');
                // Reload the page after successful deletion
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error deleting customer:', error);
                // Optionally, you can display an error message to the user
            }
        });
    }

    function redirectToDetails(customerId) {
        // Encode the customer ID before appending it to the URL
        var encodedCustomerId = encodeURIComponent(customerId);
        window.location.href = 'customer_details.php?customer_id=' + encodedCustomerId;
    }
</script>
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