<?php
// auth.php - put this line at the very top of every page that needs a login:
//     include 'auth.php';
// It starts the session and sends anyone who is not logged in back to the login page.
// A session is a small memory that the server keeps for each visitor's browser.
// session_start() opens it (it must come before anything is printed).
// $_SESSION is where we read and write what that memory holds, for example the user's id.
session_start();

// login.php saves user_id in the session after a correct login.
// isset() asks "does it exist?" and the ! in front means NOT.
// So this says: if nobody is logged in...
if (!isset($_SESSION['user_id'])) {
    // header("Location: ...") tells the browser to go to another page instead of this one.
    header("Location: login.php");
    // exit stops the script here. Without it PHP would carry on and still run the rest of the page.
    exit;
}
