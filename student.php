<?php
require 'config.php';
require_role('student');
$sid = $_SESSION['id'];
$msg = ""; $type = "success";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['drop_id'])) {
        $stmt = $conn->prepare("DELETE FROM enrollments WHERE student_id=? AND course_id=?");
        $stmt->bind_param("ii", $sid, $_POST['drop_id']);
        $stmt->execute();
        $msg = "Course dropped.";
    } else {
        $cid = (int)$_POST['course_id'];
        $stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
        $stmt->bind_param("i", $cid);
        $stmt->execute();
        $new = $stmt->get_result()->fetch_assoc();

        // CLASH DETECTION: same day AND time ranges overlap
        // overlap if existing.start < new.end  AND  existing.end > new.start
        $stmt = $conn->prepare(
            "SELECT c.code, c.name, c.start_time, c.end_time
             FROM enrollments e JOIN courses c ON c.id = e.course_id
             WHERE e.student_id=? AND c.day=? AND c.start_time < ? AND c.end_time > ?");
        $stmt->bind_param("isss", $sid, $new['day'], $new['end_time'], $new['start_time']);
        $stmt->execute();
        $clash = $stmt->get_result()->fetch_assoc();

        if ($clash) {
            $type = "danger";
            $msg = "Clash! {$new['code']} overlaps with {$clash['code']} ({$clash['name']}) on {$new['day']}, "
                 . substr($clash['start_time'],0,5) . "-" . substr($clash['end_time'],0,5) . ".";
        } else {
            $stmt = $conn->prepare("INSERT IGNORE INTO enrollments (student_id,course_id) VALUES (?,?)");
            $stmt->bind_param("ii", $sid, $cid);
            $stmt->execute();
            $msg = "Enrolled in {$new['code']} successfully.";
        }
    }
}

$order = "ORDER BY FIELD(c.day,'Mon','Tue','Wed','Thu','Fri','Sat'), c.start_time";
$mine = $conn->query("SELECT c.* FROM courses c JOIN enrollments e ON e.course_id=c.id WHERE e.student_id=$sid $order");
$all  = $conn->query("SELECT c.* FROM courses c WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id=$sid) $order");
include 'header.php';
?>
<?php if ($msg): ?><div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<div class="card p-4 mb-4">
  <h5>My Timetable</h5>
  <table class="table table-striped">
    <thead><tr><th>Code</th><th>Name</th><th>Day</th><th>Time</th><th></th></tr></thead>
    <tbody>
    <?php while ($c = $mine->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($c['code']) ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= $c['day'] ?></td>
        <td><?= substr($c['start_time'],0,5) ?> - <?= substr($c['end_time'],0,5) ?></td>
        <td><form method="post">
          <input type="hidden" name="drop_id" value="<?= $c['id'] ?>">
          <button class="btn btn-sm btn-outline-danger" data-confirm="Drop this course?">Drop</button>
        </form></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

<div class="card p-4">
  <h5>Available Courses</h5>
  <table class="table table-striped">
    <thead><tr><th>Code</th><th>Name</th><th>Day</th><th>Time</th><th></th></tr></thead>
    <tbody>
    <?php while ($c = $all->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($c['code']) ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= $c['day'] ?></td>
        <td><?= substr($c['start_time'],0,5) ?> - <?= substr($c['end_time'],0,5) ?></td>
        <td><form method="post">
          <input type="hidden" name="course_id" value="<?= $c['id'] ?>">
          <button class="btn btn-sm btn-success">Enroll</button>
        </form></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
