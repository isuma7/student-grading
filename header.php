<?php
// header.php - the top of every page: the title, the menu and a line under it.
// A page includes it with  include 'header.php';  and ends with  include 'footer.php';
// $_SESSION['username'] is the logged-in user's name (login.php saved it).
// htmlspecialchars() turns characters like < and & into harmless text, so a name cannot break the page.
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Grading System</title>
</head>
<body>
<h2>Student Grading System</h2>
<p>
    <a href="index.php">Records</a> |
    <a href="add.php">Add Result</a> |
    <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
</p>
<hr>
