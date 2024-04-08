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

$sql = "SELECT * FROM customer WHERE company_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch customer data and store in array
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
                    <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a>
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
                                <a class="text-dark" href="#" onclick="redirectToDetails(<?php echo $customer['cust_id']; ?>)">
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

                                    <form action="customer_details.php?<?php echo 'customer_id=' . $customer['cust_id'];  ?>" method="POST" class="me-2">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer['cust_id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill circle-btn c" name="delete_customer" style="margin-right: 5px;">
                                            <i class="fas fa-coins"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill circle-btn c" name="delete_customer" style="margin-right: 5px;" onclick="displayCustomerDetails(
    '<?php echo $customer['cust_id']; ?>',
    '<?php echo $customer['cust_full_name']; ?>',
    '<?php echo $customer['phone_number']; ?>',
    '<?php echo $customer['grand_balance']; ?>',
    '<?php echo $customer['address']; ?>',
    '<?php echo $customer['created_at']; ?>'
)" data-bs-toggle="modal" data-bs-target="#full-width-modal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="edit_customer.php" method="POST">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer['cust_id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill circle-btn c" name="update_customer">
                                            <i class="fas fa-edit"></i>
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
<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Customer Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerDetailsModalBody">
                <h5>Customer ID: <span id="customerId"></span></h5>
                <p>Full Name: <span id="customerFullName"></span></p>
                <p>Phone Number: <span id="customerPhoneNumber"></span></p>
                <p>Balance: <span id="customerBalance"></span></p>
                <p>Address: <span id="customerAddress"></span></p>
                <p>Created At: <span id="customerCreatedAt"></span></p>
            </div>
            <div class="modal-footer">
                <form action="update_customer.php" method="post">
                    <input type="hidden" id="customerIdInput" name="customer_id" value="">
                    <button type="submit" class="btn btn-success">Edit</button>
                </form>
                <form id="deleteCustomerForm" onsubmit="deleteCustomer(); return false;">
                    <input type="hidden" id="customerIdInput" name="customer_id" value="">
                    <button type="submit" class="btn btn-warning" data-bs-dismiss="modal">Delete</button>
                </form>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function displayCustomerDetails(customerId, fullName, phoneNumber, balance, address, createdAt) {
        // Populate modal fields with customer data
        document.getElementById('customerId').textContent = customerId;
        document.getElementById('customerFullName').textContent = fullName;
        document.getElementById('customerPhoneNumber').textContent = phoneNumber;
        document.getElementById('customerBalance').textContent = balance;
        document.getElementById('customerAddress').textContent = address;
        document.getElementById('customerCreatedAt').textContent = createdAt;
        // Set customer ID in hidden input field for forms
        document.getElementById('customerIdInput').value = customerId;
        // Show the modal
        $('#full-width-modal').modal('show');
    }

    function deleteCustomer() {
        var customerId = document.getElementById('customerIdInput').value;
        console.log('Deleting customer with ID:', customerId);
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
</body>

</html>