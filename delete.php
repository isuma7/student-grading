<?php
// delete.php - deletes one result. It has no page of its own: it deletes, then sends the user back.

// auth.php: only a logged-in user may delete. db.php: the connection to the database ($conn).
include 'auth.php';
include 'db.php';

// Only a form (POST) may delete. Typing delete.php?id=3 in the address bar is a GET and does nothing.
// $_SERVER['REQUEST_METHOD'] says how the page was asked for. "POST" means a form was sent.
// (=== means "is exactly equal to".)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The Delete form sent the id in a hidden box. ?? 0 means "use 0 if it is missing",
    // and (int) forces a whole number.
    $id = (int)($_POST['id'] ?? 0);

    // DELETE removes rows. WHERE result_id = ? limits it to ONE row: without WHERE, every row would go.
    // prepare() sends the SQL first. The ? is an empty slot for the id.
    $stmt = $conn->prepare("DELETE FROM results WHERE result_id = ?");
    // bind_param() fills the ? slot with the id. "i" means it is an integer (whole number).
    $stmt->bind_param("i", $id);
    // execute() runs the DELETE now.
    $stmt->execute();

    // affected_rows is how many rows the DELETE removed. 0 means there was no record with that id.
    if ($stmt->affected_rows > 0) {
        // A note in the session: index.php shows it once, on the next page.
        $_SESSION['message'] = "Result deleted.";
    } else {
        $_SESSION['message'] = "That record was not found.";
    }
}

// Whatever happened, go back to the Records page, then stop (exit).
header("Location: index.php");
exit;
