<?php
// edit.php - change a saved result. The record is chosen by the id in the address: edit.php?id=5

// auth.php first: it starts the session and sends anyone who is not logged in to the login page.
include 'auth.php';
include 'db.php';           // the connection to the database ($conn)
include 'functions.php';    // getGrade() and validateResult()

// $_GET holds what is in the address after the ?, so edit.php?id=5 gives $_GET['id'] = 5.
// ?? 0 means "use 0 if there is no id". (int) turns the value into a whole number, so abc becomes 0.
// The form below has no action=, so it posts back to this same address and the id is still there.
$id = (int)($_GET['id'] ?? 0);

// load the saved record
// prepare() sends the SQL to MySQL first. The ? is an empty slot for the id.
$stmt = $conn->prepare("SELECT * FROM results WHERE result_id = ?");
// bind_param() puts the real value into the ? slot. "i" means the value is an integer (whole number).
$stmt->bind_param("i", $id);
// execute() runs the SQL now.
$stmt->execute();
// get_result() collects the rows that MySQL found. fetch_assoc() takes one row out of them as an
// array whose keys are the column names, so $row['student_name'] is that column.
// If no record has this id, $row is empty (null).
$row = $stmt->get_result()->fetch_assoc();

// ! means NOT: if there is no such record, show a message and stop (exit).
if (!$row) {
    include 'header.php';
    echo "<p>Record not found.</p>";
    echo "<p><a href='index.php'>Back to records</a></p>";
    include 'footer.php';
    exit;
}

// first visit: the boxes start with the saved values
$error        = "";
$student_name = $row['student_name'];
$matric_no    = $row['matric_no'];
$subject_name = $row['subject_name'];
$subject_code = $row['subject_code'];
$test1        = $row['test1'];
$test2        = $row['test2'];
$final_exam   = $row['final_exam'];

// $_POST holds everything the form sent. The button is named "update", so $_POST['update']
// exists only after Save Changes was pressed. isset() asks "does it exist?".
if (isset($_POST['update'])) {
    // Take what was typed instead of the saved values. trim() removes spaces at the start and the end.
    $student_name = trim($_POST['student_name']);
    $matric_no    = trim($_POST['matric_no']);
    $subject_name = trim($_POST['subject_name']);
    $subject_code = trim($_POST['subject_code']);
    $test1        = trim($_POST['test1']);
    $test2        = trim($_POST['test2']);
    $final_exam   = trim($_POST['final_exam']);

    // validateResult() (in functions.php) checks all seven values. It gives back "" when all is fine,
    // or the warning text to show.
    $error = validateResult($student_name, $matric_no, $subject_name, $subject_code, $test1, $test2, $final_exam);

    if ($error == "") {
        // Add the three marks. round(..., 2) keeps 2 decimals. getGrade() works out the grade again.
        $total = round($test1 + $test2 + $final_exam, 2);
        $grade = getGrade($total);

        // UPDATE changes a row that is already saved. WHERE result_id = ? picks the ONE row to change.
        // Without WHERE, every row in the table would get these values.
        $stmt = $conn->prepare("UPDATE results
            SET student_name = ?, matric_no = ?, subject_name = ?, subject_code = ?,
                test1 = ?, test2 = ?, final_exam = ?, total_marks = ?, grade = ?
            WHERE result_id = ?");
        // One letter for each ? in the SQL, in the same order: s = string, d = decimal number, i = whole number.
        // "ssssddddsi" = 4 strings (name, matric, subject name, subject code), 4 decimals (test1, test2,
        // final, total), 1 string (grade) and 1 integer (the id, for the WHERE).
        $stmt->bind_param("ssssddddsi", $student_name, $matric_no, $subject_name, $subject_code,
                          $test1, $test2, $final_exam, $total, $grade, $id);

        // execute() runs the UPDATE now. It gives back true when it worked, and false when MySQL refused it.
        if ($stmt->execute()) {
            // Leave a note in the session. view.php shows it once, on the next page.
            $_SESSION['message'] = "Result updated.";
            // Go to the record's page, then stop (exit).
            header("Location: view.php?id=" . $id);
            exit;
        } else if ($stmt->errno == 1062) {
            // errno is the number of the MySQL error. 1062 means "duplicate entry": this student already has
            // a result for this subject (the UNIQUE line in the SQL file).
            $error = "There is already a result for " . $matric_no . " in " . $subject_code . ".";
        } else {
            // $stmt->error is the explanation that MySQL gives.
            $error = "Could not update the record. " . $stmt->error;
        }
    }
}

include 'header.php';
?>
<h3>Edit Result</h3>
<?php
if ($error != "") {
    // htmlspecialchars() turns characters like < and & into harmless text, so typed text
    // can never run as HTML or as a script.
    echo "<p><b>Warning:</b> " . htmlspecialchars($error) . "</p>";
}

// form.php prints the form. These two values tell it which button to show.
$button_name  = "update";
$button_label = "Save Changes";
include 'form.php';
?>
<p><a href="view.php?id=<?php echo $id; ?>">Cancel</a></p>
<?php include 'footer.php'; ?>
