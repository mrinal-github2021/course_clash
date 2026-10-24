<?php
require 'config.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $role  = $_POST['role'];

    $stmt = $conn->prepare("SELECT id,name,password,role FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['id']   = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: " . ($role === 'admin' ? "admin.php" : "student.php"));
        exit;
    }
    $error = "Invalid email, password or role.";
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card p-4">
      <h4 class="mb-3">Login</h4>
      <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Login as</label>
          <select name="role" class="form-select">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
          </select></div>
        <div class="mb-3"><label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
      <p class="mt-3 mb-0 text-center">New student? <a href="register.php">Register</a></p>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
