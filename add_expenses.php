<?php
session_start();
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-expenses'])) {
    // Retrieve form data
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = empty($_POST['date']) ? date('Y-m-d') : $_POST['date'];
    $status = $_POST['status'];

    // Validate form data
    if (empty($amount) || empty($description) || empty($date) || empty($status)) {
        echo "All fields are required.";
    } else {

        $stmt = $conn->prepare("INSERT INTO expenses (amount, description, date, company_id, branch_id, created_by_user_id,  status) VALUES ( ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dssiiis", $amount, $description, $date, $_SESSION['company_id'], $_SESSION['branch_id'], $_SESSION['user_id'],  $status);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Success message or redirection
            echo "Expense added successfully.";
        } else {
            // Error message
            echo "Error adding expense: " . $stmt->error;
        }
    }
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Expenses</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Expenses</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Expense</h4>
                        <form action="add_expenses.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" required class="form-control" name="amount">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" class="form-control" name="description">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="date">
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" required name="status">
                                            <option value="">Select Status</option>
                                            <option value="INCOME">Income</option>
                                            <option value="EXPENSE">Expense</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="save-expenses" class="btn btn-primary">Submit</button>
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