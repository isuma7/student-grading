<?php
// login.php - the login page: it shows the form and checks the username and password.

// The session must start before anything is printed, because a correct login saves the user in it.
session_start();
// db.php opens the database connection and gives us $conn.
include 'db.php';

// $error holds the warning to show. Empty means "no problem so far".
$error = "";
// $username remembers what was typed, so the box is not emptied when a warning appears.
$username = "";

// $_POST holds everything the form sent. The button is named "login", so $_POST['login']
// exists only after Login was pressed. isset() asks "does it exist?".
if (isset($_POST['login'])) {
    // trim() removes spaces typed by accident at the start or the end.
    $username = trim($_POST['username']);
    // The password is not trimmed, because a space can be part of a password.
    $password = $_POST['password'];

    // == "" means the box is empty. || means OR: warn when either box is empty.
    if ($username == "" || $password == "") {
        $error = "Please enter your username and your password.";
    } else {
        // Look the user up in the database with a "prepared statement". It works in four steps.
        //
        // 1. prepare() sends the SQL to MySQL first. The ? is an empty slot for the typed username.
        //    MySQL reads the SQL before it sees any typed value, so a value can never change
        //    what the SQL does. This is what stops "SQL injection".
        $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM users WHERE username = ?");
        // 2. bind_param() puts the real value into the ? slot. "s" means the value is a string (text).
        //    There is one letter for each ?, in the same order: s = string, i = whole number, d = decimal number.
        $stmt->bind_param("s", $username);
        // 3. execute() runs the SQL now, with the value filled in.
        $stmt->execute();
        // 4. get_result() collects the rows that MySQL found. fetch_assoc() takes one row out of them
        //    as an array whose keys are the column names, so $user['password_hash'] is that column.
        //    If no user has that name, $user is empty (null).
        $user = $stmt->get_result()->fetch_assoc();

        // && means AND: first check that a user was found, then check the password.
        // password_verify() compares the typed password with the hash saved in the table and gives true or false.
        // (The table never holds the real password, only a hash, which cannot be turned back into the password.)
        if ($user && password_verify($password, $user['password_hash'])) {
            // session_regenerate_id() gives the user a brand-new session id after the login.
            // It stops someone from planting a session id in the browser before the login.
            session_regenerate_id(true);
            // Remember who is logged in. auth.php looks for user_id on every other page.
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            // Send the browser to the Records page, and stop (exit) so that nothing below runs.
            header("Location: index.php");
            exit;
        } else {
            // The same message whether the username or the password was wrong, so nobody can find out
            // which usernames exist.
            $error = "Wrong username or password.";
        }
    }
}
?>
<html>
<body>
<h2>Student Grading System</h2>
<h3>Login</h3>
<?php
// htmlspecialchars() turns characters like < and & into harmless text. Anything that came from a
// user is passed through it before it is printed, so it shows as text and can never run as a script.
if ($error != "") {
    echo "<p><b>Warning:</b> " . htmlspecialchars($error) . "</p>";
}
?>
<?php
// The form. method="post" sends the values to this same page (there is no action=).
// name="username" and name="password" are the names PHP reads: $_POST['username'], $_POST['password'].
// The password box has no value=, so a typed password is never written back into the page.
?>
<form method="post">
    Username :
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br><br>
    Password :
    <input type="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
</form>
</body>
</html>
