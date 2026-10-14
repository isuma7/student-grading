<?php
// logout.php - ends the login, then goes back to the login page.

// Open this visitor's session first, so that it can be deleted.
session_start();
// session_destroy() deletes everything the session remembered, including user_id.
// From now on auth.php will send this visitor to the login page.
session_destroy();
// Go to the login page, and stop (exit) so that nothing below runs.
header("Location: login.php");
exit;
