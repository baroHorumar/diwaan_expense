<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/conn.php';
require_once 'includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = empty($_POST['date']) ? date('Y-m-d') : $_POST['date'];
    $status = $_POST['status'];
    if (empty($amount) || empty($description) || empty($date) || empty($status)) {
        echo "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE expenses SET amount=?, description=?, date=?, status=?, updated_by_user_id=?, updated_at=? WHERE expense_id=?");
        $updated_by_user_id = $_SESSION['user_id'];
        $updated_at = date('Y-m-d H:i:s');
        $stmt->bind_param("dsssisi", $amount, $description, $date, $status, $updated_by_user_id, $updated_at, $_POST['expense_id']);
        if ($stmt->execute()) {
            echo "<script>window.location.href = './expenses.php';</script>";

            exit(); // Ensure script execution stops after redirection
        } else {
            echo "Error updating expense: " . $stmt->error;
        }
    }
}
// Check if expense ID is provided and valid
if (isset($_POST['expense_id']) && is_numeric($_POST['expense_id'])) {
    $expenseId = $_POST['expense_id'];

    // Fetch the expense details from the database
    $sql = "SELECT * FROM expenses WHERE expense_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expenseId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if expense exists
    if ($result->num_rows > 0) {
        $expense = $result->fetch_assoc();
?>
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Expense</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="expenses.php">Expense</a></li>
                                <li class="breadcrumb-item active">Update Expense</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Expense</h4>
                                <form action="update_expenses.php" method="POST">
                                    <input type="hidden" name="expense_id" value="<?php echo $expenseId; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" required class="form-control" name="amount" value="<?php echo $expense['amount']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" class="form-control" name="description" value="<?php echo $expense['description']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" name="date" value="<?php echo $expense['date']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" required name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="INCOME" <?php if ($expense['status'] == 'INCOME') echo 'selected'; ?>>Income</option>
                                                    <option value="EXPENSE" <?php if ($expense['status'] == 'EXPENSE') echo 'selected'; ?>>Expense</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        echo "Expense not found";
    }
} else {
    echo "Invalid or missing expense ID.";
}
?>

<?php
require './includes/footer.php';
?>