<?php
require 'includes/header.php';
session_start();
require 'includes/conn.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT user_id, company_id, branch_id FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // Fetch user details
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $company_id = $row['company_id'];
    $branch_id = $row['branch_id'];

    // Login successful
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['company_id'] = $company_id; // Corrected variable name
    $_SESSION['branch_id'] = $branch_id;

    header('Location: index.php');
    exit;
  } else {
    // Login failed
    $_SESSION['login'] = false;
    header('Location: login.php'); // Redirect back to login page
    exit;
  }
}
?>

<style>
  .card {
    max-width: 400px;
    width: 100%;
    height: auto;
    margin: 0 auto;
  }
</style>
<main class="main" id="top">
  <div class="container-fluid">
    <div class="row min-vh-100 flex-center g-0">
      <div class="col-12 d-flex justify-content-center align-items-center">
        <div class="card overflow-hidden">
          <div class="card-body p-4">
            <h3 class="text-center mb-4">Account Login</h3>
            <form action="login.php" method="post">
              <div class="mb-3">
                <label class="form-label" for="card-email">Email address</label>
                <input class="form-control" name="email" id="card-email" type="text" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="card-password">Password</label>
                <input class="form-control" name="password" id="card-password" type="password" />
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="card-checkbox" checked="checked" />
                  <label class="form-check-label" for="card-checkbox">Remember me</label>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Log in</button>
              </div>
            </form>
            <div class="text-center dont-have">don't have an account? <a href="register.php">Register Now</a></div>

          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require 'includes/footer.php'; ?>