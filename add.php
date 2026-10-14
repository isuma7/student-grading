<?php
// add.php - the form to key in a new result, and the code that saves it.

// auth.php first: it starts the session and sends anyone who is not logged in to the login page.
include 'auth.php';
// db.php gives us $conn, the connection to the database.
include 'db.php';
// functions.php gives us getGrade() and validateResult().
include 'functions.php';

// These start empty, so the boxes have something to show the first time the page opens.
// After a warning they hold what the user typed, so nothing has to be typed again.
$error        = "";
$student_name = "";
$matric_no    = "";
$subject_name = "";
$subject_code = "";
$test1        = "";
$test2        = "";
$final_exam   = "";

// $_POST holds everything the form sent. The button is named "submit", so $_POST['submit']
// exists only after the button was pressed. isset() asks "does it exist?".
if (isset($_POST['submit'])) {
    // Read what was typed. trim() removes spaces typed by accident at the start and the end.
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

    // No warning, so the values are good. (== "" means empty.)
    if ($error == "") {
        // Add the three marks. round(..., 2) keeps 2 decimals and removes tiny computer errors,
        // such as 0.1 + 0.2 becoming 0.30000000000000004.
        // getGrade() (in functions.php) turns the total into A, B, C, D or E.
        $total = round($test1 + $test2 + $final_exam, 2);
        $grade = getGrade($total);

        // Save the record with a "prepared statement", in three steps.
        // 1. prepare() sends the SQL to MySQL first. Each ? is an empty slot for one value (there are nine).
        //    MySQL reads the SQL before it sees any typed value, so a value can never change what the SQL does.
        $stmt = $conn->prepare("INSERT INTO results
            (student_name, matric_no, subject_name, subject_code, test1, test2, final_exam, total_marks, grade)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // 2. bind_param() puts the real values into the nine ? slots, in the same order.
        //    One letter for each value tells MySQL its type: s = string (text), d = decimal number, i = whole number.
        //    "ssssdddds" = 4 strings (name, matric, subject name, subject code), 4 decimals (test1, test2,
        //    final, total) and 1 string (grade).
        $stmt->bind_param("ssssdddds", $student_name, $matric_no, $subject_name, $subject_code,
                          $test1, $test2, $final_exam, $total, $grade);

        // 3. execute() runs the SQL now. It gives back true when the record was saved, and false when
        //    MySQL refused it.
        if ($stmt->execute()) {
            // Leave a note in the session. view.php shows it once, on the next page.
            $_SESSION['message'] = "Result saved.";
            // insert_id is the id that MySQL gave to the row we have just saved.
            // header("Location: ...") sends the browser to that record's page, and exit stops this script.
            header("Location: view.php?id=" . $conn->insert_id);
            exit;
        } else if ($stmt->errno == 1062) {
            // errno is the number of the MySQL error. 1062 means "duplicate entry": this student already has
            // a result for this subject (the UNIQUE line in the SQL file).
            $error = "There is already a result for " . $matric_no . " in " . $subject_code . ". Use Edit to change it.";
        } else {
            // $stmt->error is the explanation that MySQL gives.
            $error = "Could not save the record. " . $stmt->error;
        }
    }
}

// Now the page itself. header.php prints the title and the menu.
include 'header.php';
?>
<h3>Add Result</h3>
<?php
if ($error != "") {
    // htmlspecialchars() turns characters like < and & into harmless text, so typed text
    // can never run as HTML or as a script.
    echo "<p><b>Warning:</b> " . htmlspecialchars($error) . "</p>";
}

// form.php prints the form. These two values tell it which button to show.
$button_name  = "submit";
$button_label = "Submit";
include 'form.php';

include 'footer.php';
?>
