<?php
session_start();
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fetch_query = "SELECT password FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($fetch_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($current_password);
    $stmt->fetch();
    $previous_password = $_POST['previouspass'];
    if (password_verify($previous_password, $current_password)) {
        $new_password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = './profile.php';</script>";
                exit();
            } else {
                $errorMessage = "Failed to update password";
            }
        } else {
            $errorMessage = "New password and confirm password do not match";
        }
    } else {
        $errorMessage = "Previous password is incorrect";
    }
}

?>
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="loginbox">
            <div class="login-right">
                <div class="container">
                    <?php
                    if (isset($errorMessage)) {
                        echo $errorMessage;
                    }
                    ?>
                    <div class="login-right-wrap">
                        <h1>Badal Passwordka</h1>
                        <p class="account-subtitle">Ku soo Dhawaw Diwaan</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                            <div class="form-group">
                                <label class="form-control-label">Password kii Hore</label>
                                <input class="form-control" type="password" name="previouspass">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Passwordka Cusub</label>
                                <input class="form-control" type="password" name="password">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Xaqiiji Password</label>
                                <input class="form-control" type="password" name="confirm_password">
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-lg btn-block btn-primary w-100" type="submit" name="register">Register</button>
                            </div>
                        </form>
                        <div class="text-center dont-have">Already have an account? <a href="login.php">Login</a></div>
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

<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/js/script.js"></script>
</body>

</html>