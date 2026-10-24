// Ask for confirmation before any action marked with data-confirm
document.querySelectorAll('[data-confirm]').forEach(function (btn) {
  btn.addEventListener('click', function (e) {
    if (!confirm(btn.getAttribute('data-confirm'))) e.preventDefault();
  });
});

// Check end time is after start time on the add-course form
var form = document.getElementById('courseForm');
if (form) {
  form.addEventListener('submit', function (e) {
    if (form.start_time.value >= form.end_time.value) {
      alert('End time must be after start time.');
      e.preventDefault();
    }
  });
}
