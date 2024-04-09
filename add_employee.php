<?php
session_start();
include './includes/header.php';
include './includes/sidebar.php';
include './includes/conn.php';
$errorMessage = '';
$cust_full_name = $phone_number = $address = $username = $password = $confirmPassword = $hire_date =  $salary = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $cust_full_name = $_POST['cust_full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $salary = $_POST['salary'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password']; // New line to retrieve confirm password
    $hire_date = $_POST['hire_date'];
    $deactivation_date = null; // Initialize deactivation_date
    $role = 'employee'; // Set role to employee
    $status = 1; // Set status to active
    $company_id = $_SESSION['company_id'];
    $created_by = $_SESSION['user_id'];
    $errorMessage = '';

    if ($password !== $confirmPassword) {
        $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Passwords do not match.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        $stmt_check_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();

        if ($result_check_username->num_rows === 0) {
            // Insert data into the users table
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert_user = $conn->prepare("INSERT INTO users (full_name, username, password, registration_date, deactivation_date, role, status, company_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert_user->bind_param("ssssssii", $cust_full_name, $username, $hashedPassword, date('Y-m-d'), $deactivation_date, $role, $status, $company_id);
            if ($stmt_insert_user->execute()) {
                // Retrieve the user_id of the inserted row
                $user_id = $stmt_insert_user->insert_id;

                // Insert data into the employee table
                $stmt_insert_employee = $conn->prepare("INSERT INTO employee (full_name, phone_number, hire_date, is_active, user_id, company_id, created_by, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $is_active = 1;
                $stmt_insert_employee->bind_param("sssiiidi", $cust_full_name, $phone_number, $hire_date, $is_active, $user_id, $company_id, $created_by, $salary);
                if ($stmt_insert_employee->execute()) {
                    echo "<script>window.location.href = 'all_employees.php';</script>";
                    exit;
                } else {
                    // Error message
                    $errorMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Error adding employee: ' . $stmt_insert_employee->error . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            } else {
                // Error message
                $errorMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Error adding user: ' . $stmt_insert_user->error . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        } else {
            // Username already exists
            $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Username already taken!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}
?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Shaqaale</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">kudar Shaqaale</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php echo $errorMessage; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Kudar Shaqaale</h4>
                        <form action="add_employee.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Magaca oo Dhamaystiran</label>
                                        <input type="text" required class="form-control" name="cust_full_name" value="<?php echo htmlspecialchars($cust_full_name); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Telephone ka Shaqaale</label>
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Taariikhda shaqaalaysiinta</label>
                                        <input type="date" class="form-control" name="hire_date" value="<?php echo htmlspecialchars($hire_date); ?>">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary</label>
                                        <input type="text" class="form-control" name="salary" value="<?php echo htmlspecialchars($salary); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($password); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Xaqiiji Password</label>
                                        <input type="password" class="form-control" name="confirm_password" value="<?php echo htmlspecialchars($confirmPassword); ?>">
                                    </div>

                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="add_employee" class="btn btn-primary">Submit</button>
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