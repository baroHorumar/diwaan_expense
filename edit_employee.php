<?php
session_start();
include './includes/header.php';
include './includes/sidebar.php';
include './includes/conn.php';
$errorMessage = '';
$cust_full_name = $phone_number = $username = $password = $confirmPassword = $hire_date = $salary = $status = ""; // Initialize status variable
$employee_id = isset($_POST['employee_id']) ? $_POST['employee_id'] : ''; // Initialize employee_id variable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_update'])) {
    $cust_full_name = $_POST['cust_full_name'];
    $phone_number = $_POST['phone_number'];
    $salary = $_POST['salary'];
    $username = $_POST['username']; // Remove the username assignment here
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $hire_date = $_POST['hire_date'];
    $status = $_POST['status'];

    if ($password !== $confirmPassword) {
        $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Passwords do not match.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        $stmt_employee = $conn->prepare("UPDATE employee SET full_name=?, phone_number=?,  salary=? WHERE employee_id=?");
        $stmt_employee->bind_param("sssi", $cust_full_name, $phone_number,  $salary, $employee_id);
        if ($stmt_employee->execute()) {
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt_user = $conn->prepare("UPDATE users SET full_name=?, password=?, status=? WHERE user_id=?");
                $stmt_user->bind_param("ssii", $cust_full_name, $hashedPassword, $status, $_SESSION[' $user_id_empl']);
            } else {
                $stmt_user = $conn->prepare("UPDATE users SET full_name=?, password=?, status=? WHERE user_id=?");
                $stmt_user->bind_param("ssii", $cust_full_name, $password, $status, $employee_id);
            }
            if ($stmt_user->execute()) {
                echo "<script>window.location.href = 'all_employees.php';</script>";
                exit;
            } else {
                // Error message
                $errorMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Error updating employee: ' . $stmt_user->error . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        } else {
            // Error message
            $errorMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Error updating employee: ' . $stmt_employee->error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $employee_id = $_POST['employee_id'];
    $stmt_fetch_employee = $conn->prepare("SELECT * FROM employee WHERE employee_id = ?");
    $stmt_fetch_employee->bind_param("i", $employee_id);
    $stmt_fetch_employee->execute();
    $result_employee = $stmt_fetch_employee->get_result();

    if ($result_employee->num_rows === 1) {
        $row_employee = $result_employee->fetch_assoc();
        $cust_full_name = $row_employee['full_name'];
        $phone_number = $row_employee['phone_number'];
        $salary = $row_employee['salary'];
        $_SESSION[' $user_id_empl'] = $row_employee['user_id'];

        // Fetch user details associated with the employee
        $stmt_fetch_user = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt_fetch_user->bind_param("i", $user_id_empl);
        $stmt_fetch_user->execute();
        $result_user = $stmt_fetch_user->get_result();

        if ($result_user->num_rows === 1) {
            $row_user = $result_user->fetch_assoc();
            $username = $row_user['username'];
            $status = $row_user['status'];
        }
    } else {
        // Employee not found
        $errorMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Employee not found.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
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
                        <form action="edit_employee.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Magaca oo Dhamaystiran</label>
                                        <input type="text" required class="form-control" name="cust_full_name" value="<?php echo htmlspecialchars($cust_full_name); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Telephone ka Shaqaale</label>
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($password); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Xaqiiji Password</label>
                                        <input type="password" class="form-control" name="confirm_password" value="<?php echo htmlspecialchars($confirmPassword); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Disable</option>
                                            <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Enable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
                                <button type="submit" name="employee_update" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include './includes/footer.php';
?>