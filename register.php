<?php
require 'config.php';
$msg = ""; $type = "danger";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $hash  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'student')");
    $stmt->bind_param("sss", $name, $email, $hash);
    try {
        $stmt->execute();
        $msg = "Registered! You can now log in."; $type = "success";
    } catch (mysqli_sql_exception $e) {
        $msg = "That email is already registered.";
    }
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card p-4">
      <h4 class="mb-3">Student Registration</h4>
      <?php if ($msg): ?><div class="alert alert-<?= $type ?>"><?= $msg ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" minlength="6" required></div>
        <button class="btn btn-primary w-100">Register</button>
      </form>
      <p class="mt-3 mb-0 text-center"><a href="login.php">Back to login</a></p>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
