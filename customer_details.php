<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}
require './includes/header.php';
require './includes/sidebar.php';
include './includes/conn.php';
if (isset($_POST['customer_details'])) {
    $customer_id = $_POST['customer_id'];
    $sql = "SELECT cust_id,cust_full_name, phone_number, grand_balance FROM customer WHERE cust_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cust_full_name = $row['cust_full_name'];
        $phone_number = $row['phone_number'];
        $grand_balance = $row['grand_balance'];
        $cust_id123 = $row['cust_id'];
    }

    $sql_transactions = "SELECT cust_tra_id, amount, created_at, status, description, return_date, total FROM cust_transaction WHERE customer_id = ? AND status = 'Dayn'";
    $stmt_transactions = $conn->prepare($sql_transactions);
    $stmt_transactions->bind_param("i", $cust_id123);
    $stmt_transactions->execute();
    $result_transactions = $stmt_transactions->get_result();


    $recivable_transactions = "SELECT cust_tra_id, amount, created_at, status, description, return_date, total FROM cust_transaction WHERE customer_id = ? AND status != 'Dayn'";
    $recivablestm_transactions = $conn->prepare($recivable_transactions);
    $recivablestm_transactions->bind_param("i", $cust_id123);
    $recivablestm_transactions->execute();
    $recivable_result = $recivablestm_transactions->get_result(); // Corrected variable name

?>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-xl-12 col-lg-12">
                <div class="row">
                    <div class="col-xl-12 col-sm-10 col-12">
                        <div class="card inovices-card">
                            <div class="card-body">
                                <div class="inovices-widget-header">
                                    <span class="inovices-widget-icon">
                                        <img src="assets/img/icons/invoices-icon1.svg" alt="">
                                    </span>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount">
                                            <?php echo $cust_full_name; ?>
                                        </div>
                                    </div>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount">
                                            <?php echo $phone_number; ?>
                                        </div>
                                    </div>
                                </div>
                                <p><span class="font-weight-bold display-4">
                                        <?php echo $grand_balance; ?>
                                    </span></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-12 col-lg-12">
                <div class="col-md-12 col-sm-3 col-lg-12">
                    <div class="card bg-white">
                        <div class="card-header">
                            <h5 class="card-title">Maaraynta Macmiilka</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                                <li class="nav-item"><a class="nav-link active" href="#solid-rounded-justified-tab1" data-bs-toggle="tab">Accounts Payable</a></li>
                                <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab2" data-bs-toggle="tab">Accounts Recievable</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="solid-rounded-justified-tab1">
                                    <div class="tab-pane show active" id="solid-rounded-justified-tab1">
                                        <div class="invoices-settings-btn invoices-settings-btn-one">
                                            <a href="add_cust_transaction.php" class="btn">
                                                <i data-feather="plus-circle"></i> Lacag Qaadasho
                                            </a>
                                        </div>
                                        <div class="row">
                                            <?php
                                            if ($result_transactions->num_rows > 0) {
                                                while ($row_transaction = $result_transactions->fetch_assoc()) {
                                                    $amount = $row_transaction['amount'];
                                                    $due_date = date('j-M-Y', strtotime($row_transaction['created_at'])); // Format the date
                                                    $status = $row_transaction['status'];
                                                    $total = $row_transaction['total'];
                                                    $return_date = $row_transaction['return_date'];
                                                    $description = $row_transaction['description'];
                                                    $cust_transaction_id = $row_transaction['cust_tra_id'];
                                            ?>
                                                    <div class="col-md-6 col-lg-4 col-xl-4 d-flex mb-3">
                                                        <div class="card invoices-grid-card w-100">
                                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                                <a href="view-invoice.html" class="invoice-grid-link"><?php echo $status . '      ' . $due_date; ?></a>
                                                                <div class="dropdown dropdown-action">
                                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="edit_cust_transaction.php?customer_id=<?php echo $cust_transaction_id; ?>"><i class="far fa-edit me-2"></i>Edit</a>
                                                                        <a class="dropdown-item" href="#" onclick="showTransactionDetails(<?php echo $cust_transaction_id; ?>)">
                                                                            <i class="far fa-eye me-2"></i>View
                                                                        </a> <a class="dropdown-item" href="delete_cust_transaction.php?customer_id=<?php echo $cust_transaction_id; ?>">
                                                                            <i class="far fa-trash-alt me-2"></i>Delete
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-money-bill-alt"></i> Amount</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $amount; ?></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-calendar-alt"></i> Due Date</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $due_date; ?></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-file-alt"></i> Description</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $description; ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <?php if ($status == 'Amaah') {
                                                                        ?>
                                                                            <div class="d-flex justify-content-between ">
                                                                                <span class="badge bg-success-dark m-1"><?php echo $status; ?></span>
                                                                                <span class="badge <?php echo (strtotime($return_date) > time()) ? 'bg-success-dark' : 'bg-warning'; ?> m-1"><?php echo date('j-M-Y', strtotime($return_date)); ?></span>
                                                                                <span class="badge bg-primary m-1"><i class="fas fa-money-bill"></i> Qabasho</span>
                                                                            </div>
                                                                        <?php
                                                                        } else {

                                                                        ?>
                                                                            <div class="d-flex justify-content-between  ">
                                                                                <span class="badge bg-success-dark m-2 tex-center"><?php echo $status; ?></span>
                                                                                <span class="badge bg-success-dark m-2"><i class="fas fa-money-bill"></i><?php echo ' ' . $total; ?></span>
                                                                            </div>
                                                                        <?php
                                                                        } ?>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                            <?php
                                                } // end while loop
                                            } else {
                                                echo "No transactions found.";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="solid-rounded-justified-tab2">
                                    <div class="tab-pane" id="solid-rounded-justified-tab2">
                                        <div class="invoices-settings-btn invoices-settings-btn-one">
                                            <a href="add_cust_transaction.php" class="btn">
                                                <i data-feather="plus-circle"></i> Lacag Dhigasho
                                            </a>
                                        </div>
                                        <div class="row">
                                            <?php
                                            if ($recivable_result->num_rows > 0) {
                                                while ($row_transaction = $recivable_result->fetch_assoc()) {
                                                    $amount = $row_transaction['amount'];
                                                    $due_date = date('j-M-Y', strtotime($row_transaction['created_at'])); // Format the date
                                                    $status = $row_transaction['status'];
                                                    $total = $row_transaction['total'];
                                                    $return_date = $row_transaction['return_date'];
                                                    $description = $row_transaction['description'];
                                                    $cust_transaction_id = $row_transaction['cust_tra_id'];
                                            ?>
                                                    <div class="col-md-6 col-lg-4 col-xl-4 d-flex mb-3">
                                                        <div class="card invoices-grid-card w-100">
                                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                                <a href="view-invoice.html" class="invoice-grid-link"><?php echo $status . '      ' . $due_date; ?></a>
                                                                <div class="dropdown dropdown-action">
                                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="edit_cust_transaction.php?customer_id=<?php echo $cust_transaction_id; ?>"><i class="far fa-edit me-2"></i>Edit</a>
                                                                        <a class="dropdown-item" href="#" onclick="showTransactionDetails(<?php echo $cust_transaction_id; ?>)">
                                                                            <i class="far fa-eye me-2"></i>View
                                                                        </a> <a class="dropdown-item" href="delete_cust_transaction.php?customer_id=<?php echo $cust_transaction_id; ?>">
                                                                            <i class="far fa-trash-alt me-2"></i>Delete
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-money-bill-alt"></i> Amount</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $amount; ?></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-calendar-alt"></i> Due Date</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $due_date; ?></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <span><i class="far fa-file-alt"></i> Description</span>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <h6 class="mb-0"><?php echo $description; ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <?php if ($status == 'Amaah') {
                                                                        ?>
                                                                            <div class="d-flex justify-content-between ">
                                                                                <span class="badge bg-success-dark m-1"><?php echo $status; ?></span>
                                                                                <span class="badge <?php echo (strtotime($return_date) > time()) ? 'bg-success-dark' : 'bg-warning'; ?> m-1"><?php echo date('j-M-Y', strtotime($return_date)); ?></span>
                                                                                <span class="badge bg-primary m-1"><i class="fas fa-money-bill"></i> Qabasho</span>
                                                                            </div>
                                                                        <?php
                                                                        } else {

                                                                        ?>
                                                                            <div class="d-flex justify-content-between  ">
                                                                                <span class="badge bg-success-dark m-2 tex-center"><?php echo $status; ?></span>
                                                                                <span class="badge bg-success-dark m-2"><i class="fas fa-money-bill"></i><?php echo ' ' . $total; ?></span>
                                                                            </div>
                                                                        <?php
                                                                        } ?>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                            <?php
                                                } // end while loop
                                            } else {
                                                echo "No transactions found.";
                                            }
                                            ?>
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

    <div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fullWidthModalLabel">Customer Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerDetailsModalBody">
                    <h5>Transaction Details</h5>
                    <p>Transaction ID: <span id="custTraId"></span></p>
                    <p>Amount: <span id="amount"></span></p>
                    <p>Total: <span id="total"></span></p>
                    <p>Created By: <span id="createdBy"></span></p>
                    <p>Updated By: <span id="updatedBy"></span></p>
                    <p>Created At: <span id="createdAt"></span></p>
                    <p>Updated At: <span id="updatedAt"></span></p>
                    <p>Status: <span id="status"></span></p>
                    <p>Company ID: <span id="companyId"></span></p>
                    <p>Customer ID: <span id="customerId"></span></p>
                    <p>Transaction Payment: <span id="transPayment"></span></p>
                    <p>Description: <span id="description"></span></p>
                    <p>Money Type: <span id="moneyType"></span></p>
                    <p>Return Date: <span id="returnDate"></span></p>
                    <p>Actual Return Date: <span id="actualReturnDate"></span></p>
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
        function confirmDelete(transaction_id) {
            if (confirm("Are you sure you want to delete this transaction?")) {
                window.location.href = "delete_cust_transaction.php?customer_id=<?php echo $custmor_trans_id; ?>&transaction_id=" + transaction_id;
            }
        }
    </script>
    <script>
        function showTransactionDetails(transaction_id) {
            $.ajax({
                url: 'fetch_transaction_details.php', // Create this PHP file to handle AJAX request
                type: 'POST',
                data: {
                    transaction_id: transaction_id
                },
                success: function(response) {
                    var transaction = JSON.parse(response);
                    $('#custTraId').text(transaction.cust_tra_id);
                    $('#amount').text(transaction.amount);
                    $('#total').text(transaction.total);
                    $('#createdBy').text(transaction.created_by);
                    $('#updatedBy').text(transaction.updated_by);
                    $('#createdAt').text(transaction.created_at);
                    $('#updatedAt').text(transaction.updated_at);
                    $('#status').text(transaction.status);
                    $('#companyId').text(transaction.company_id);
                    $('#customerId').text(transaction.customer_id);
                    $('#transPayment').text(transaction.trans_payment);
                    $('#description').text(transaction.description);
                    $('#moneyType').text(transaction.money_type);
                    $('#returnDate').text(transaction.return_date);
                    $('#actualReturnDate').text(transaction.actual_return_date);

                    $('#full-width-modal').modal('show'); // Show the modal
                }
            });
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
<?php

} else {
    echo "Customer ID not provided";
}
?>
