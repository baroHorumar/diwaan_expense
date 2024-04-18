<?php
session_start();
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';

if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit(); // Ensure script stops execution after redirection
}

if (!isset($_GET['expense_id'])) {
    header('location:expenses.php'); // Redirect to expenses page
    exit(); // Ensure script stops execution after redirection
}

$expense_id = $_GET['expense_id'];
$sql = "SELECT * FROM expenses WHERE expense_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expense_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('location:expenses.php'); // Redirect to expenses page
    exit(); // Ensure script stops execution after redirection
}

$expense = $result->fetch_assoc();

// Fetch user details for created_by_user_id
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expense['created_by_user_id']);
$stmt->execute();
$created_by_result = $stmt->get_result();
$created_by_user = $created_by_result->fetch_assoc();

// Fetch user details for updated_by_user_id
$stmt->bind_param("i", $expense['updated_by_user_id']);
$stmt->execute();
$updated_by_result = $stmt->get_result();
$updated_by_user = $updated_by_result->fetch_assoc();

$stmt->close();
$conn->close();
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
                                    Faahfaahinta Dakhli/Kharash:
                                    <p class="mb-0">Warbixin Kooban oo Ku saabsan Dakhli/Kharash</p>
                                </div>

                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="invoice-total-card">
                                    <div class="invoice-total-box">
                                        <div class="invoice-total-inner">
                                            <p>
                                                Description: <strong><?php echo  ' ' . $expense['description']; ?></strong></p>
                                            <p>
                                                Date:<strong><?php echo  ' ' . $expense['date']; ?></strong></p>
                                            <p>
                                                Branch ID:<strong><?php echo  ' ' . $expense['branch_id']; ?></strong></p>
                                            <p>
                                                Created By:<strong><?php echo  ' ' . $created_by_user['full_name']; ?></strong></p>
                                            <p>
                                                Updated By:<strong><?php echo  ' ' . $updated_by_user['full_name']; ?></strong></p>
                                            <p>
                                                Created At:<strong><?php echo  ' ' . $expense['created_at']; ?></strong></p>
                                            <p>
                                                Updated At:<strong><?php echo  ' ' . $expense['updated_at']; ?></strong></p>
                                            <p>
                                                Status:<strong><?php echo  ' ' . $expense['status']; ?></strong></p>
                                            <div class="invoice-total-footer">
                                                <h4>Total Amount <span><?php echo $expense['amount']; ?></span></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3"> <!-- Add margin top for space -->
                                        <div class="btn-group me-3" role="group" aria-label="Invoice Buttons">
                                            <form id="deleteForm" action="delete_expense.php" method="post">
                                                <input type="hidden" name="expense_id" value="<?php echo $expense['expense_id']; ?>">
                                                <button type="submit" class="btn btn-warning mx-2">Delete</button>
                                            </form>
                                            <button type="button" class="btn btn-primary mx-2" onclick="window.location.href = 'expenses.php';">Close</button>
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