<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Course Clash Detector</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary mb-4">
  <div class="container">
    <span class="navbar-brand">Course Clash Detector</span>
    <?php if (isset($_SESSION['name'])): ?>
      <span class="text-white">
        <?= htmlspecialchars($_SESSION['name']) ?> (<?= $_SESSION['role'] ?>)
        <a href="logout.php" class="btn btn-sm btn-light ms-2">Logout</a>
      </span>
    <?php endif; ?>
  </div>
</nav>
<div class="container">
