<?php
// index.php - the Records page: lists every result, with Search and the View, Edit and Delete buttons.

// auth.php first: it starts the session and sends anyone who is not logged in to the login page.
include 'auth.php';
include 'db.php';       // the connection to the database ($conn)

// $_GET holds what is in the address after the ?, so index.php?q=aisyah gives $_GET['q'] = "aisyah".
// ?? "" means "use an empty text if there is no q". trim() removes spaces at the start and the end.
$q    = trim($_GET['q'] ?? "");
// LIKE '%aisyah%' means "contains aisyah". % stands for any characters, so one goes on each side.
$like = "%" . $q . "%";

// Search with a "prepared statement", in four steps.
// 1. prepare() sends the SQL to MySQL first. Each ? is an empty slot for a typed word.
//    There are four, because the same word is checked against four columns (OR means "either").
//    (If nothing was typed, $like is %% which matches every row, so this also lists everything.)
$stmt = $conn->prepare("SELECT * FROM results
    WHERE student_name LIKE ? OR matric_no LIKE ? OR subject_name LIKE ? OR subject_code LIKE ?
    ORDER BY student_name");
// 2. bind_param() puts the real values into the four ? slots. One letter for each: s = string (text),
//    i = whole number, d = decimal number. All four are strings here, so "ssss".
$stmt->bind_param("ssss", $like, $like, $like, $like);
// 3. execute() runs the SQL now.
$stmt->execute();
// 4. get_result() collects all the rows that MySQL found, ready to be read one by one in the loop below.
$result = $stmt->get_result();

include 'header.php';

// Another page may have left a note in the session (for example "Result deleted.").
// Show it once. unset() then deletes it, so it does not appear again when the page is refreshed.
if (isset($_SESSION['message'])) {
    echo "<p><b>" . htmlspecialchars($_SESSION['message']) . "</b></p>";
    unset($_SESSION['message']);
}
?>
<h3>Student Records</h3>
<p><a href="add.php">+ Add Result</a></p>

<?php
// The search form uses method="get", so the word typed shows up in the address (index.php?q=aisyah).
// name="q" is the name PHP reads: $_GET['q']. The box shows the last word searched.
?>
<form method="get">
    <input type="text" name="q" size="40" placeholder="Student name, matric number, subject name or code"
           value="<?php echo htmlspecialchars($q); ?>">
    <input type="submit" value="Search">
    <a href="index.php">Show all</a>
</form>
<br>

<table border="1" cellpadding="6">
    <tr>
        <th>No.</th>
        <th>Student name</th>
        <th>Matric no.</th>
        <th>Subject</th>
        <th>Test 1 (20)</th>
        <th>Test 2 (30)</th>
        <th>Final (50)</th>
        <th>Total (100)</th>
        <th>Grade</th>
        <th>Actions</th>
    </tr>
<?php
// $no only counts the rows on the screen, for the "No." column. It is not the record's id.
$no = 1;
// fetch_assoc() takes the next row out of $result as an array, so $row['student_name'] is that column.
// When there are no rows left it gives null and the while loop ends. So the loop runs once per row.
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $no . "</td>";
    // htmlspecialchars() turns characters like < and & into harmless text, so a name can never run as HTML.
    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['matric_no']) . "</td>";
    echo "<td>" . htmlspecialchars($row['subject_name']) . " (" . htmlspecialchars($row['subject_code']) . ")</td>";
    // number_format($x, 2) prints a number with exactly 2 decimals (18 becomes 18.00).
    echo "<td>" . number_format($row['test1'], 2) . "</td>";
    echo "<td>" . number_format($row['test2'], 2) . "</td>";
    echo "<td>" . number_format($row['final_exam'], 2) . "</td>";
    echo "<td><b>" . number_format($row['total_marks'], 2) . "</b></td>";
    echo "<td><b>" . htmlspecialchars($row['grade']) . "</b></td>";
    echo "<td>";
    // (int) forces the id to be a whole number, so only digits can ever be written into a link.
    echo "<a href='view.php?id=" . (int)$row['result_id'] . "'>View</a> ";
    echo "<a href='edit.php?id=" . (int)$row['result_id'] . "'>Edit</a> ";
    // Delete is a small form (method='post'), not a link, so it needs a real button click.
    // onsubmit='return confirm(...)' shows an OK / Cancel box first. Cancel returns false, which stops the form.
    echo "<form method='post' action='delete.php' style='display:inline' onsubmit='return confirm(\"Delete this record?\")'>";
    echo "<input type='hidden' name='id' value='" . (int)$row['result_id'] . "'>";
    echo "<input type='submit' value='Delete'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
    $no++;
}
?>
</table>
<?php
// num_rows is how many rows the search found. 0 means nothing matched.
if ($result->num_rows == 0) {
    if ($q != "") {
        echo "<p>No records match your search.</p>";
    } else {
        echo "<p>No records yet.</p>";
    }
}
include 'footer.php';
?>
