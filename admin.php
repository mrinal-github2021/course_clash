<?php
require 'config.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
        $stmt->bind_param("i", $_POST['delete_id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (code,name,day,start_time,end_time) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $_POST['code'], $_POST['name'], $_POST['day'], $_POST['start_time'], $_POST['end_time']);
        $stmt->execute();
    }
    header("Location: admin.php"); exit;
}
$courses = $conn->query("SELECT * FROM courses ORDER BY FIELD(day,'Mon','Tue','Wed','Thu','Fri','Sat'), start_time");
include 'header.php';
?>
<div class="card p-4 mb-4">
  <h5>Add Course</h5>
  <form method="post" id="courseForm" class="row g-2">
    <div class="col-md-2"><input name="code" class="form-control" placeholder="Code" required></div>
    <div class="col-md-3"><input name="name" class="form-control" placeholder="Course name" required></div>
    <div class="col-md-2">
      <select name="day" class="form-select">
        <option>Mon</option><option>Tue</option><option>Wed</option>
        <option>Thu</option><option>Fri</option><option>Sat</option>
      </select></div>
    <div class="col-md-2"><input type="time" name="start_time" class="form-control" required></div>
    <div class="col-md-2"><input type="time" name="end_time" class="form-control" required></div>
    <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
  </form>
</div>

<div class="card p-4">
  <h5>All Courses</h5>
  <table class="table table-striped">
    <thead><tr><th>Code</th><th>Name</th><th>Day</th><th>Start</th><th>End</th><th></th></tr></thead>
    <tbody>
    <?php while ($c = $courses->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($c['code']) ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= $c['day'] ?></td>
        <td><?= substr($c['start_time'],0,5) ?></td>
        <td><?= substr($c['end_time'],0,5) ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
            <button class="btn btn-sm btn-danger" data-confirm="Delete this course?">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
