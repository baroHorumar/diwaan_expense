<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit();
}

require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']); // Sanitize the input

    $sql_transactions = "SELECT amount, customer_id, created_at, status, money_type, description, return_date FROM cust_transaction WHERE cust_tra_id = ?";
    $stmt_transactions = $conn->prepare($sql_transactions);
    $stmt_transactions->bind_param("i", $customer_id);
    $stmt_transactions->execute();
    $result_transactions = $stmt_transactions->get_result();

    if ($row = $result_transactions->fetch_assoc()) {
        $amount = $row['amount'];
        $status = $row['status'];
        $return_date = $row['return_date'];
        $description = $row['description'];
        $money_type = $row['money_type'];
        $custmor_trans_id = $row['customer_id'];
    } else {
        echo "No transaction found for the provided ID";
        exit(); // Exit if no transaction found
    }

    if (isset($_POST['edit_transaction'])) {
        $new_amount = $_POST['amount'];
        $new_date = $_POST['date'];
        $new_status = $_POST['status'];
        $new_trans_payment = $_POST['trans_payment'];
        $new_description = $_POST['description']; // Add new description

        // Fetch the current grand balance
        $query = "SELECT grand_balance FROM customer WHERE cust_id = ?";
        $stmt_balance = $conn->prepare($query);
        $stmt_balance->bind_param("i", $customer_id);
        $stmt_balance->execute();
        $result_balance = $stmt_balance->get_result();
        $row_balance = $result_balance->fetch_assoc();
        $grand_balance = $row_balance['grand_balance'];

        // Calculate the change in grand balance based on the new status
        if ($new_status == 'Dhigasho') {
            // If status is 'Dhigasho', decrease balance for refund
            $grand_balance += $new_amount;
        } else {
            // If status is not 'Dhigasho', increase balance for new transaction
            $grand_balance -= $new_amount;
        }

        // Prepare and execute query to update grand_balance in customer table
        $update_balance_query = "UPDATE customer SET grand_balance = ? WHERE cust_id = ?";
        $stmt_balance_update = $conn->prepare($update_balance_query);
        $stmt_balance_update->bind_param("di", $grand_balance, $customer_id);
        $stmt_balance_update->execute();

        // Update cust_transaction including description and return_date
        $sql_update = "UPDATE cust_transaction SET amount=?, created_at=?, status=?, money_type=?, description=?, return_date=?, created_by=?, total=? WHERE cust_tra_id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssiid", $new_amount, $new_date, $new_status, $new_trans_payment, $new_description, $new_date, $_SESSION['user_id'], $grand_balance, $customer_id);

        if ($stmt_update->execute()) {
            echo "Transaction updated successfully.";
            header("Location: customer_details.php?customer_id=" . urlencode($custmor_trans_id));
            exit();
        } else {
            echo "Error updating transaction: " . $conn->error;
        }
    }


?>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-xl-10 col-lg-12">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Transaction</h4>
                                <form method="POST" action="edit_cust_transaction.php?customer_id=<?php echo htmlspecialchars($customer_id); ?>">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" name="amount" value="<?php echo $amount; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="date" value="<?php echo $return_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                            <option value="Dhigasho">Dhigasho</option>
                                            <option value="Qaadasho">Qaadasho</option>
                                            <option value="Amaah">Amaah</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nooca Lacagta</label>
                                        <select class="form-control" name="trans_payment">
                                            <option value="<?php echo $status; ?>"><?php echo $money_type; ?></option>
                                            <option value="Dollar">Dollar</option>
                                            <option value="Sl sh">Kaash</option>
                                            <option value="Bir">Bir</option>
                                        </select>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="edit_transaction" class="btn btn-primary">Update Transaction</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    require './includes/footer.php';
} else {
    echo "Customer ID not provided";
}
?>