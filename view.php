<?php
// view.php - shows ONE result in full. The record is chosen by the id in the address: view.php?id=5

// auth.php first: it starts the session and sends anyone who is not logged in to the login page.
include 'auth.php';
include 'db.php';       // the connection to the database ($conn)

// $_GET holds what is in the address after the ?, so view.php?id=5 gives $_GET['id'] = 5.
// ?? 0 means "use 0 if there is no id in the address". (int) turns the value into a whole number,
// so text such as abc becomes 0 and finds nothing.
$id = (int)($_GET['id'] ?? 0);

// Get this one record with a "prepared statement" (prepare, bind_param, execute, get_result).
// prepare() sends the SQL to MySQL first. The ? is an empty slot for the id. The id is never joined
// into the SQL text, because it came from the address bar and anyone can type anything there.
$stmt = $conn->prepare("SELECT * FROM results WHERE result_id = ?");
// bind_param() puts the real value into the ? slot. "i" means the value is an integer (whole number).
// (s = string, d = decimal number.)
$stmt->bind_param("i", $id);
// execute() runs the SQL now.
$stmt->execute();
// get_result() collects the rows that MySQL found. fetch_assoc() takes one row out of them as an
// array whose keys are the column names, so $row['student_name'] is that column.
// If no record has this id, $row is empty (null).
$row = $stmt->get_result()->fetch_assoc();

include 'header.php';

// Another page may have left a note in the session ("Result saved." or "Result updated.").
// Show it once. unset() then deletes it, so it does not appear again when the page is refreshed.
if (isset($_SESSION['message'])) {
    echo "<p><b>" . htmlspecialchars($_SESSION['message']) . "</b></p>";
    unset($_SESSION['message']);
}

// ! means NOT: "if there is no record". Show a message, finish the page and stop (exit).
if (!$row) {
    echo "<p>Record not found.</p>";
    echo "<p><a href='index.php'>Back to records</a></p>";
    include 'footer.php';
    exit;
}

// From here on $row is a real record. In the table below, htmlspecialchars() makes text safe to print
// and number_format($x, 2) prints a number with 2 decimals (18 becomes 18.00).
?>
<h3>Result for <?php echo htmlspecialchars($row['student_name']); ?></h3>
<table border="1" cellpadding="6">
    <tr><th align="left">Student name</th><td><?php echo htmlspecialchars($row['student_name']); ?></td></tr>
    <tr><th align="left">Matric number</th><td><?php echo htmlspecialchars($row['matric_no']); ?></td></tr>
    <tr><th align="left">Subject name</th><td><?php echo htmlspecialchars($row['subject_name']); ?></td></tr>
    <tr><th align="left">Subject code</th><td><?php echo htmlspecialchars($row['subject_code']); ?></td></tr>
    <tr><th align="left">Test 1 (out of 20)</th><td><?php echo number_format($row['test1'], 2); ?></td></tr>
    <tr><th align="left">Test 2 (out of 30)</th><td><?php echo number_format($row['test2'], 2); ?></td></tr>
    <tr><th align="left">Final examination (out of 50)</th><td><?php echo number_format($row['final_exam'], 2); ?></td></tr>
    <tr><th align="left">Total marks (out of 100)</th><td><b><?php echo number_format($row['total_marks'], 2); ?></b></td></tr>
    <tr><th align="left">Final grade</th><td><b><?php echo htmlspecialchars($row['grade']); ?></b></td></tr>
</table>
<p>
    <a href="edit.php?id=<?php echo (int)$row['result_id']; ?>">Edit</a> |
    <a href="index.php">Back to records</a>
</p>
<?php
// Delete is a form with method="post", not a link, so it needs a real button click.
// onsubmit="return confirm(...)" shows an OK / Cancel box first. Cancel returns false, which stops the form.
// (int) makes sure only a whole number is written into the hidden id box.
?>
<form method="post" action="delete.php" onsubmit="return confirm('Delete this record?')">
    <input type="hidden" name="id" value="<?php echo (int)$row['result_id']; ?>">
    <input type="submit" value="Delete">
</form>
<?php include 'footer.php'; ?>
