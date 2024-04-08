<?php
session_start();
include './includes/header.php';
include './includes/sidebar.php';
include './includes/conn.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_customer'])) {
    // Retrieve form data
    $cust_id = $_POST['cust_id'];
    $cust_full_name = $_POST['cust_full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $updated_by = $_SESSION['user_id'];

    // Validate form data
    if (empty($cust_full_name) || empty($phone_number) || empty($address)) {
        echo "All fields are required.";
    } else {
        // Update customer data in the database
        $stmt = $conn->prepare("UPDATE customer SET cust_full_name=?, phone_number=?, address=?, updated_by=? WHERE cust_id=?");
        $stmt->bind_param("sssii", $cust_full_name, $phone_number, $address, $updated_by, $cust_id);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'all_customers.php';</script>";
            exit;
        } else {
            // Error message
            echo "Error updating customer: " . $stmt->error;
        }
    }
}
// Check if the form for updating a customer is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    // Retrieve customer ID from the form
    $cust_id = $_POST['customer_id'];

    // Retrieve customer data from the database
    $stmt = $conn->prepare("SELECT * FROM customer WHERE cust_id = ?");
    $stmt->bind_param("i", $cust_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if customer exists
    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
    } else {
        echo "Customer not found.";
        exit;
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
                        <form action="edit_customer.php" method="POST">
                            <input type="hidden" name="cust_id" value="<?php echo $customer['cust_id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" required class="form-control" name="cust_full_name" value="<?php echo $customer['cust_full_name']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo $customer['phone_number']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address" value="<?php echo $customer['address']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="edit_customer" class="btn btn-primary">Submit</button>
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