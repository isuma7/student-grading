<?php
// db.php - the database connection. Every page that needs the database includes this file.
// There is no closing PHP tag at the end of this file, on purpose: a blank line after
// a closing tag is sent to the browser and can break header() redirects.

mysqli_report(MYSQLI_REPORT_OFF);   // a failed query returns false, so we can check it and show our own message
// new mysqli(host, user, password, database). If your MySQL has a password, type it in the empty quotes.
$conn = new mysqli("localhost", "root", "sayang123", "student_grading");
// $conn (short for connection) is what every other page uses to talk to the database.

// connect_error is empty when the connection worked, and holds the reason when it did not.
// die() prints a message and stops the page right there.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Send and receive text as UTF-8, so names with accents or other alphabets are not damaged.
$conn->set_charset("utf8mb4");
